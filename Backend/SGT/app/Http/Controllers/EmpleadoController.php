<?php

namespace App\Http\Controllers;

use App\Models\Brigada;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EmpleadoController extends Controller
{
    public function index()
    {
        return response()->json(['empleados' => Empleado::all()]);
    }

    public function store(Request $request)
    {


        $data = $request->validate([
            'ci' => 'required',
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccionLocal' => 'required',
        ]);
        return Empleado::create($data);
    }
    public function update(Request $request, $ci)
    {
        try {

            $estudiante = Empleado::where('ci',$ci)->first();
            $data = $request->validate([
                'ci' => 'required',
                'nombre' => 'required',
                'apellidos' => 'required',
                'direccionLocal' => 'required',
            ]);
            $estudiante->update($request->all());
            return response()->json(['estudiante' => $estudiante], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al modificar el empleado'], $e);
        }
    }

    public function destroy($ci)
    {
        try {
            $empleado = Empleado::where('ci', $ci)->first();
            $empleado->delete();
            return response()->json(['message' => 'Empleado ' . $ci . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el empleado'], 500);
        }
    }

    public function asignarBrigada($brigada_id, $empleado_id)
    {
        try {
            $empleado = Empleado::findOrFail($empleado_id);
            $empleado->brigada_id = $brigada_id;
            $empleado->save();

            return response()->json(['message' => 'Asignacion de brigada completada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar la brigada'], 500);
        }
    }
    public function desAsignarBrigada($empleado_id)
    {
        try {
            $empleado = Empleado::findOrFail($empleado_id);
            $empleado->brigada_id = null;
            $empleado->save();

            return response()->json(['message' => 'Desasignacion de brigada completada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al desasignar la brigada'], 500);
        }
    }

    public function empleadosSinBrigada()
    {
        // Obtener todos los empleados con brigada_id null
        $empleadosSinBrigada = Empleado::whereNull('brigada_id')->get();

        return response()->json(['empleados' => $empleadosSinBrigada]);
    }

    public function empleadosPorBrigada($brigadaId)
    {
        // Obtener todos los empleados asignados a la brigada específica
        $empleadosPorBrigada = Empleado::where('brigada_id', $brigadaId)->get();

        return response()->json(['empleados' => $empleadosPorBrigada]);
    }

    public function cantEmpleadosBrigada($brigada_id)
    {

        $cantEmpleados = Empleado::where('brigada_id', $brigada_id)->get()->count();

        return $cantEmpleados;
    }
}
