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

    public function destroy($id)
    {
        try {

            $brigada = Brigada::findOrFail($id);
            $brigada->delete();
            return response()->json(['message' => 'Brigada ' . $id . ' eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la brigada'], 500);
        }
    }
}
