<?php

use App\Http\Controllers\BrigadaController;
use App\Http\Controllers\EmpleadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('brigadas',[BrigadaController::class, 'index']);
Route::post('brigadas',[BrigadaController::class, 'store']);
Route::delete('brigadas/{id}',[BrigadaController::class, 'destroy']);

Route::get('empleados',[EmpleadoController::class, 'index']);
Route::post('empleados',[EmpleadoController::class, 'store']);
Route::delete('empleados/{id}',[EmpleadoController::class, 'destroy']);
Route::post('empleados/brigada_{brigada_id}/empleado_{empleado_id}',[EmpleadoController::class, 'asignarBrigada']);

