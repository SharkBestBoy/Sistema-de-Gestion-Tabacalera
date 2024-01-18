<?php

namespace App\Http\Controllers;

use App\Models\Vitola;
use Illuminate\Http\Request;

class VitolaController extends Controller
{
    public function index()
    {
        return response()->json(['vitolas' => Vitola::all()]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'nombre' => 'required',
            'categoria' => 'required',
        ]);
        return Vitola::create($data);
    }

    public function destroy($id)
    {
        try {

            $brigada = Vitola::findOrFail($id);
            $brigada->delete();
            return response()->json(['message' => 'Vitola ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la vitola'], 500);
        }
    }

    public function vitolasPorCategoria(Request $request)
    {
        try {

            $vitolas = Vitola::where('categoria', $request->categoria)->pluck('nombre')->toArray();

            return response()->json(['vitolas' => $vitolas]);
        } catch (\Exception $e) {
            // Manejar errores, por ejemplo, devolver un código de error y un mensaje
            return response()->json(['error' => 'Error al obtener las vitolas', 'message' => $e->getMessage()], 500);
        }
    }

    public function obtenerIdPorNombre(Request $request)
    {
        try {
            // Validar la solicitud para asegurarse de que contiene el campo 'nombre'
            $request->validate([
                'nombre' => 'required|string',
            ]);

            // Obtener el ID de la vitola por su nombre
            $nombreVitola = $request->input('nombre');
            $vitola = Vitola::where('nombre', $nombreVitola)->first();

            if ($vitola) {
                // Devolver el ID si se encontró la vitola
                return response()->json(['id' => $vitola->id]);
            } else {
                // Devolver una respuesta adecuada si la vitola no se encontró
                return response()->json(['message' => 'Vitola no encontrada'], 404);
            }
        } catch (\Exception $e) {
            // Manejar excepciones, por ejemplo, devolver un error 500
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }
}
