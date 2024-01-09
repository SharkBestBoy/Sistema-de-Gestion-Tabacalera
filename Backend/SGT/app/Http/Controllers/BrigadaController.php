<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brigada;

class BrigadaController extends Controller
{
    
    public function index()
    {
        return response()->json(['brigadas'=>Brigada::all()]);
    }

}
