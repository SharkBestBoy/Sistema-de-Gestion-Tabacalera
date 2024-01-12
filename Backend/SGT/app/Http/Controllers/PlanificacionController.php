<?php

namespace App\Http\Controllers;

use App\Models\Fecha;
use App\Models\Planificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanificacionController extends Controller
{
    public function index()
    {
        // Obtener todas las fechas con planificaciones
        $fechasConPlanificacion = Fecha::whereNotNull('planificacion_id')->get();
    
        // Inicializar el array resultante
        $planificacionesPorMes = [];
    
        // Agrupar las fechas por mes
        $fechasAgrupadas = $fechasConPlanificacion->groupBy('mes');
    
        // Iterar sobre cada grupo (mes)
        foreach ($fechasAgrupadas as $mes => $fechas) {
            // Obtener la planificación asociada (suponiendo que hay una relación)
            $planificacion = $fechas->first()->planificacion;
    
            // Convertir el número de mes a nombre
            $nombreMes = Carbon::create()->month($mes)->monthName;
    
            // Agregar al array resultante
            $planificacionesPorMes[] = [
                $nombreMes => $planificacion->planificacionMensual
            ];
        }
    
        return response()->json(['planificaciones' => $planificacionesPorMes]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'planificacionMensual' => 'required',
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
}
