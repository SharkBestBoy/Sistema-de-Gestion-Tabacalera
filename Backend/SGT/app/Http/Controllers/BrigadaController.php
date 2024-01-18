<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brigada;

class BrigadaController extends Controller
{

    public function index()
    {
        
        return response()->json(['brigadas' => Brigada::all()]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'numero' => 'required',
        ]);
        return Brigada::create($data);
    }

    public function destroy($brigada_id)
    {
        try {

            $brigada = Brigada::findOrFail($brigada_id);
            $brigada->delete();
            return response()->json(['message' => 'Brigada ' . $brigada_id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la brigada'], 500);
        }
    }

    public function obtenerIdPorNumero(Request $request)
    {
        try {
            $numeroBrigada = $request->input('numero');
            
            $brigada = Brigada::where('numero', $numeroBrigada)->first();

            if ($brigada) {
                return response()->json(['id' => $brigada->id]);
            } else {
                return response()->json(['message' => 'No se encontró la brigada con ese número'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener el ID de la brigada', $e], 500);
        }
    }



}
