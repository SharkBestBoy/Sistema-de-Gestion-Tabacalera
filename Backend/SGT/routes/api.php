<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BrigadaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FechaController;
use App\Http\Controllers\PlanificacionController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\VitolaController;
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

//  Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//      return $request->user();
//  });
Route::get('brigadas', [BrigadaController::class, 'index']);
Route::post('brigadas', [BrigadaController::class, 'store']);
Route::delete('brigadas/{id}', [BrigadaController::class, 'destroy']);


Route::get('empleados', [EmpleadoController::class, 'index']);
Route::post('empleados', [EmpleadoController::class, 'store']);
Route::delete('empleados/{id}', [EmpleadoController::class, 'destroy']);
Route::post('empleados/brigada_{brigada_id}/empleado_{empleado_id}', [EmpleadoController::class, 'asignarBrigada']);


Route::get('produccions', [ProduccionController::class, 'index']);
Route::post('produccions', [ProduccionController::class, 'store']);
Route::delete('produccions/{id}', [ProduccionController::class, 'destroy']);
Route::get('produccions/suma/{fecha_id}', [ProduccionController::class, 'sumaProduccionPorDia']);
Route::get('produccions/porcentajeDiario/{fechaID}', [ProduccionController::class, 'calcularPorcentajeCumplimiento']);


Route::get('vitolas', [VitolaController::class, 'index']);
Route::post('vitolas', [VitolaController::class, 'store']);
Route::delete('vitolas/{id}', [VitolaController::class, 'destroy']);

Route::post('addFecha', [FechaController::class, 'crearFechas']);

Route::get('planificacions', [PlanificacionController::class, 'index']);
Route::post('planificacions', [PlanificacionController::class, 'store']);
Route::get('planificacions/{planificacion_id}', [PlanificacionController::class, 'calcularPlanificacionDiaria']);

Route::get('fechas', [FechaController::class, 'buscarFecha_ID']);



// !!!PARA LOGIN !!!
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('users', [AuthController::class, 'allUsers']);