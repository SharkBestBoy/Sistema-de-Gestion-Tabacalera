<?php

namespace App\Http\Controllers;

use App\Models\Fecha;
use App\Models\Planificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PlanificacionController extends Controller
{

    public function index()
    {
        // Obtener todas las planificaciones y ordenarlas por año y mes
        $planificaciones = Planificacion::all()->sortBy(function ($planificacion) {
            // Obtener el primer registro de fechas asociadas a la planificación
            $primeraFecha = $planificacion->fechas->first();

            // Devolver una cadena que representa el año y mes en el formato "YYYYMM"
            return $primeraFecha->anno . str_pad($primeraFecha->mes, 2, '0', STR_PAD_LEFT);
        });

        $meses = array(
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        );

        // Crear un array para almacenar los resultados
        $resultados = [];

        // Iterar sobre cada planificación y obtener la información requerida
        foreach ($planificaciones as $planificacion) {
            // Obtener el primer registro de fechas asociadas a la planificación
            $primeraFecha = $planificacion->fechas->first();

            // Obtener el nombre del mes
            $fecha = Carbon::createFromDate(null, $primeraFecha->mes, null);

            $nombreMes = $meses[$fecha->format('n') - 1];
            $infoPlanificacion = [
                'id' => $planificacion->id,
                'mes' => $nombreMes,
                'anno' => $primeraFecha->anno,
                'planificacion' => $planificacion->planificacionMensual,
                'diasLaborables' => $planificacion->diasLaborables,
            ];

            // Agregar la información al array de resultados
            $resultados[] = $infoPlanificacion;
        }

        // Devolver el resultado como JSON
        return response()->json(['planificaciones' => $resultados]);
    }


    public function store(Request $request)
    {

        $data = $request->validate([
            'planificacionMensual' => 'required',
            'diasLaborables' => 'required',
            'mes' => 'required',
            'anno' => 'required',
        ]);
        $creado = Planificacion::create($data);

        $fechaController = app(FechaController::class);
        $fechaController->agregarPlanificacionMensual($creado->id, $data['mes'], $data['anno']);
        return response()->json('Creado correctamente');
    }

    public function destroy($id)
    {
        try {

            $planificacion = Planificacion::findOrFail($id);
            $planificacion->delete();
            return response()->json(['message' => 'Planificación ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la planificación'], 500);
        }
    }

    public function calcularPlanificacionDiaria($planificacion_id)
    {
        // Obtener la planificación por su ID
        $planificacion = Planificacion::findOrFail($planificacion_id);

        // Verificar si la planificación tiene días laborables mayores a cero
        if ($planificacion->diasLaborables > 0) {
            // Calcular la planificación diaria
            $planificacionDiaria = $planificacion->planificacionMensual / $planificacion->diasLaborables;

            // Formatear el resultado a 0 decimales
            $planificacionDiaria = number_format($planificacionDiaria, 0);

            // Devolver la planificación diaria
            return $planificacionDiaria;
            // return response()->json(['planificacionDiaria' => $planificacionDiaria]);
        } else {
            // Si los días laborables son cero, devolver un mensaje de error
            return response()->json(['error' => 'Los días laborables deben ser mayores a cero']);
        }
    }

    public function verificarPlanificacion($mes, $anno)
    {
        // Buscar una planificación para el mes y año dados
        $planificacion = Planificacion::whereHas('fechas', function ($query) use ($mes, $anno) {
            $query->where('mes', $mes)->where('anno', $anno);
        })->first();

        if ($planificacion) {
            // Hay una planificación para el mes y año dados
            return response()->json(['existe' => true, 'planificacionMensual' => $planificacion->planificacionMensual, 'id' => $planificacion->id, 'mensaje' => 'Hay una planificación para el mes y año dados']);
        } else {
            // No hay una planificación para el mes y año dados
            return response()->json(['existe' => false, 'mensaje' => 'No hay una planificación para el mes y año dados']);
        }
    }
}
