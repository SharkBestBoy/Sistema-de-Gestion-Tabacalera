<?php

namespace App\Http\Controllers;

use App\Models\Brigada;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        return response()->json(['empleados' => Empleado::all()]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccionLocal' => 'required',
        ]);
        return Empleado::create($data);
    }

    public function destroy($id)
    {
        try {

            $brigada = Empleado::findOrFail($id);
            $brigada->delete();
            return response()->json(['message' => 'Empleado ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el empleado'], 500);
        }
    }

    public function asignarBrigada($brigada_id, $empleado_id)
    {
        try{
        $empleado = Empleado::findOrFail($empleado_id);
        $empleado->brigada_id = $brigada_id;
        $empleado->save();

        return response()->json(['message' => 'Asignacion de brigada completada']);
    }catch (\Exception $e) {
        return response()->json(['error' => 'Error al asignar la brigada'], 500);
    }

    }

}
