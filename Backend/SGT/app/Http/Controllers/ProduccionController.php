<?php

namespace App\Http\Controllers;

use App\Models\Brigada;
use App\Models\Fecha;
use App\Models\Produccion;
use App\Models\Vitola;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProduccionController extends Controller

{
    public function index()
    {

        return response()->json(['produccions' => Produccion::all()]);
    }

    public function store(Request $request)
    {
        try {
            $producciones = $request->validate([
                '*.cant_producida' => 'required',
                '*.cant_trabajadores' => 'required',
                '*.vitola_id' => 'required',
                '*.brigada_id' => 'required',
                '*.fecha_id' => 'required',
            ]);
            foreach ($producciones as $produccion) {
                Produccion::create($produccion);
            };
            return "Bien";
        } catch (\Exception $e) {
            // Manejar la excepción de validación aquí
            return response()->json(['message' => 'Alguno de los IDs proporcionados no existe', 'error' => $e], 404);
        }
    }

    public function destroy($id)
    {
        try {

            $produccion = Produccion::findOrFail($id);
            $produccion->delete();
            return response()->json(['message' => 'Producción ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la producción'], 500);
        }
    }

    public function produccionesPorFecha($dia, $mes, $anno)
    {
        $fecha = Fecha::where('anno', $anno)
            ->where('mes', $mes)
            ->where('dia', $dia)
            ->firstOrFail(); // Obtener la fecha correspondiente

        $producciones = Produccion::where('fecha_id', $fecha->id)
            ->with(['vitola', 'brigada']) // Cargar relaciones
            ->get(); // Obtener las producciones de esa fecha con las relaciones cargadas

        // Transformar los resultados para incluir nombres y números en lugar de IDs
        $produccionesTransformadas = $producciones->map(function ($produccion) {
            return [
                'id' => $produccion->id,
                'cant_producida' => $produccion->cant_producida,
                'cant_trabajadores' => $produccion->cant_trabajadores,
                'vitola' => [
                    'nombre' => $produccion->vitola->nombre,
                    'categoria' => $produccion->vitola->categoria,
                ],
                'brigada' => [
                    'numero' => $produccion->brigada->numero,
                ],
                'created_at' => $produccion->created_at,
                'updated_at' => $produccion->updated_at,
            ];
        });

        return response()->json(['producciones' => $produccionesTransformadas]);
    }


    public function sumaProduccionPorDia($fechaID)
    {
        // Obtener la fecha específica
        $fecha = Fecha::find($fechaID);

        if (!$fecha) {
            return response()->json(['error' => 'Fecha no encontrada'], 404);
        }
        $fecha_id = $fecha->id;

        // Obtener las producciones asociadas a la fecha
        $producciones = Produccion::where('fecha_id', $fecha_id)->get();

        // Calcular la suma de la cantidad de producciones
        $sumaProduccion = $producciones->sum('cant_producida');

        return response()->json(['suma_produccion' => $sumaProduccion]);
    }

    public function calcularPorcentajeCumplimiento($fechaId)
    {
        // Obtener la producción diaria
        $sumaProduccionDiaria = $this->sumaProduccionPorDia($fechaId);

        $data = json_decode($sumaProduccionDiaria->content(), true);
        $produccionDiaria = $data['suma_produccion'];
        // Obtener la planificación id a partir de la fecha
        $fecha = Fecha::find($fechaId);

        if (!$fecha) {
            return response()->json(['error' => 'Fecha no encontrada'], 404);
        }

        $planificacionId = $fecha->planificacion_id;
        // Verificar si la planificación id es válida
        if (!$planificacionId) {
            return response()->json(['error' => 'La fecha no tiene una planificación asociada'], 404);
        }

        // ARREGLAR ESTO QUE ME ESTA LLAMANDO AL METODO CALCULARPLANIFICACIONDIARIA CON FECHAID Y NO CON PLANIFICACIONID
        // Obtener la planificación diaria

        $planificacionController = app(PlanificacionController::class);
        $planificacionDiaria = $planificacionController->calcularPlanificacionDiaria($planificacionId);

        // Calcular el porcentaje de cumplimiento
        $porcentajeCumplimiento = 0;
        if ($planificacionDiaria > 0) {
            $porcentajeCumplimiento = ($produccionDiaria / $planificacionDiaria) * 100;
        }

        return number_format($porcentajeCumplimiento, 2);
    }

    public function obtenerInformacionMensual($mes, $anno)
    {
        try {
            // Obtener todos los días del mes y año que tienen producciones
            $fechasConProduccion = Produccion::join('fechas', 'produccions.fecha_id', '=', 'fechas.id')
                ->where('fechas.mes', $mes)
                ->where('fechas.anno', $anno)
                ->get();
            // Inicializar arrays para almacenar resultados
            $resultados = [];
            foreach ($fechasConProduccion as $produccion) {
                $fechaId = $produccion->fecha_id;
                // Sumar las cantidades producidas y la cantidad de trabajadores por fecha_id
                $resultados[$fechaId]['dia'] = $produccion->dia; // Agregar la clave "dia"
                $resultados[$fechaId]['cant_producida'] = ($resultados[$fechaId]['cant_producida'] ?? 0) + $produccion->cant_producida;
                $resultados[$fechaId]['cant_trabajadores'] = ($resultados[$fechaId]['cant_trabajadores'] ?? 0) + $produccion->cant_trabajadores;

                // Calcular el porcentaje de cumplimiento por fecha_id
                $porcentajeCumplimiento = $this->calcularPorcentajeCumplimiento($fechaId);
                $resultados[$fechaId]['porcentaje_cumplimiento'] = $porcentajeCumplimiento;
            }
            $planificacionController = app(PlanificacionController::class);
            $planDiario = $planificacionController->calcularPlanificacionDiaria($produccion->planificacion_id);

            return response()->json(['dias' => $resultados, 'planDiario' => $planDiario]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
    public function produccionTotalMes($mes, $anno)
    {

        // Obtener las fechas en el mes y año especificados
        $fechas = Fecha::where('mes', $mes)
            ->where('anno', $anno)
            ->get();

        // Inicializar la suma total
        $sumaTotal = 0;

        // Iterar sobre las fechas y sumar las producciones
        foreach ($fechas as $fecha) {
            $fecha_id = $fecha->id;

            // Obtener las producciones asociadas a la fecha
            $producciones = Produccion::where('fecha_id', $fecha_id)->get();

            // Calcular la suma de la cantidad de producciones
            $sumaProduccion = $producciones->sum('cant_producida');

            // Sumar al total
            $sumaTotal += $sumaProduccion;
        }

        return response()->json(['suma_produccion_mes' => $sumaTotal]);
    }

    public function sumaProduccionVitolaIX()
    {
        // Obtener la ID de la vitola con categoría IX
        $vitolaIds = Vitola::where('categoria', 'IX')->pluck('id');

        // Verificar si se encontraron vitolas con categoría IX
        if ($vitolaIds->isEmpty()) {
            return response()->json(['error' => 'Vitolas con categoría IX no encontradas'], 404);
        }

        // Obtener las producciones asociadas a las vitolas con categoría IX
        $producciones = Produccion::whereIn('vitola_id', $vitolaIds)->get();

        // Calcular la suma de la cantidad de producciones
        $sumaProduccion = $producciones->sum('cant_producida');

        return response()->json(['suma_produccion_vitola_ix' => $sumaProduccion]);
    }


    public function sumaProduccionVitolaVIII()
    {
        try {
            // Obtener la ID de la vitola con categoría IX
            $vitolaIds = Vitola::where('categoria', 'VIII')->pluck('id');

            // Verificar si la vitola con categoría IX existe
            if ($vitolaIds->isEmpty()) {
                return response()->json(['error' => 'Vitola con categoría VIII no encontrada'], 404);
            }
            // Obtener las producciones asociadas a la vitola con categoría IX
            
            $producciones = Produccion::whereIn('vitola_id', $vitolaIds)->get();
            // if ($producciones) {
            //     return response()->json(['error' => 'No Existen producciones con categoria VIII', 'suma_produccion_vitola_vii' => 0], 404);
            // }
            // Calcular la suma de la cantidad de producciones
            $sumaProduccion = $producciones->sum('cant_producida');

            return response()->json(['suma_produccion_vitola_viii' => $sumaProduccion]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
    public function sumaProduccionVitolaVII()
    {
        // Obtener la ID de la vitola con categoría IX
        $vitolaIds = Vitola::where('categoria', 'VII')->pluck('id');

        // Verificar si la vitola con categoría IX existe
        if ($vitolaIds->isEmpty()) {
            return response()->json(['error' => 'Vitola con categoría VII no encontrada'], 404);
        }

        // Obtener las producciones asociadas a la vitola con categoría IX
        $producciones = Produccion::whereIn('vitola_id', $vitolaIds)->get();
        
        // Calcular la suma de la cantidad de producciones
        $sumaProduccion = $producciones->sum('cant_producida');

        return response()->json(['suma_produccion_vitola_vii' => $sumaProduccion]);
    }
}
