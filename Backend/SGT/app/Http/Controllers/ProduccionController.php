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
        return response()->json(['producciones' => Produccion::all()]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'cantidad' => 'required',
                'vitola_id' => 'required',
                'brigada_id' => 'required',

            ]);

            return Produccion::create($data);
        } catch (\Exception $e) {
            // Manejar la excepción de validación aquí
            return response()->json(['message' => 'Alguno de los IDs proporcionados no existe'], 404);
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
}
