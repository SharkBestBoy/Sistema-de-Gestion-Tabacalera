<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use Illuminate\Http\Request;

class ProduccionController extends Controller

{
    public function index()
    {
        return response()->json(['empleados' => Produccion::all()]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'cantidad' => 'required',
            'vitola_id' => 'required',
        ]);
        
        return Produccion::create($data);
    }

    public function destroy($id)
    {
        try {

            $brigada = Produccion::findOrFail($id);
            $brigada->delete();
            return response()->json(['message' => 'Produccion ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el empleado'], 500);
        }
    }

    public function asignarBrigada($brigada_id, $empleado_id)
    {
        try {
            $empleado = Produccion::findOrFail($empleado_id);
            $empleado->brigada_id = $brigada_id;
            $empleado->save();

            return response()->json(['message' => 'Asignacion de brigada completada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar la brigada'], 500);
        }
    }
}
