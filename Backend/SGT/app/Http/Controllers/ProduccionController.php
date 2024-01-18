<?php

namespace App\Http\Controllers;

use App\Models\Brigada;
use App\Models\Fecha;
use App\Models\Produccion;
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
                '*.cantidad' => 'required',
                '*.vitola_id' => 'required',
                '*.brigada_id' => 'required',
                '*.fecha_id' => 'required',
            ]);
            
            return Produccion::createMany($producciones);
        } catch (\Exception $e) {
            // Manejar la excepción de validación aquí
            return response()->json(['message' => 'Alguno de los IDs proporcionados no existe', $e], 404);
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

    public function produccionesPorFecha(Request $request, $anno, $mes, $dia)
    {
        $fecha = Fecha::where('anno', $anno)
            ->where('mes', $mes)
            ->where('dia', $dia)
            ->firstOrFail(); // Obtener la fecha correspondiente

        $producciones = Produccion::where('fecha_id', $fecha->id)->get(); // Obtener las producciones de esa fecha

        return response()->json(['producciones' => $producciones]);
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
        $sumaProduccion = $producciones->sum('cantidad');

        return response()->json(['suma_produccion' => $sumaProduccion]);
    }

    public function calcularPorcentajeCumplimiento($fechaId)
    {
        // Obtener la producción diaria
        $sumaProduccionDiaria = $this->sumaProduccionPorDia($fechaId);

        $data = json_decode($sumaProduccionDiaria->content(),true);
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
        $planificacionDiaria= json_decode($planificacionDiaria->content())->planificacionDiaria;
         
        // Calcular el porcentaje de cumplimiento
        $porcentajeCumplimiento = 0;
        if ($planificacionDiaria > 0) {
            $porcentajeCumplimiento = ($produccionDiaria / $planificacionDiaria) * 100;
        }

        return response()->json(['porcentajeCumplimiento' => $porcentajeCumplimiento]);
    }
}

