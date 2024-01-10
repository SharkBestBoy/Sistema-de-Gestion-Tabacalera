<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use Illuminate\Http\Request;

class PlanificacionController extends Controller
{
    public function index()
    {
        return response()->json(['planificaciones' => Planificacion::all()]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'planificacionMensual' => 'required',
        ]);
        return Planificacion::create($data);
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
