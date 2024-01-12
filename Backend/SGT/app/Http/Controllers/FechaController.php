<?php

namespace App\Http\Controllers;

use App\Models\Fecha;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FechaController extends Controller
{
    public function crearFechas()
    {
        $fechaInicio = Carbon::create(2023, 1, 1); // Fecha de inicio
        $fechaFin = Carbon::create(2023, 12, 31); // Fecha de fin

        $fechas = [];
        for ($fecha = $fechaInicio; $fecha->lte($fechaFin); $fecha->addDay()) {
            $fechas[] = [
                'anno' => $fecha->year,
                'mes' => $fecha->month,
                'dia' => $fecha->day,
            ];
        }

        Fecha::insert($fechas); // Insertar todas las fechas en la base de datos
    }

    public function agregarPlanificacionMensual($planificacion_id, $mes, $anno)
    {

        $fechas = Fecha::where('mes', $mes)
            ->where('anno', $anno)
            ->get();

        // Asignar la planificación a cada fecha
        foreach ($fechas as $fecha) {
            $fecha->planificacion_id = $planificacion_id;
            $fecha->save();
        }
        return response()->json(['message' => 'Planificación asignada a fechas correctamente']);
    }

};
