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
    }}
