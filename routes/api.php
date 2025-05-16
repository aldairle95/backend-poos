<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
      // ✅ Solo Admin (o quienes tengan permiso)
      Route::middleware('permission:gestionar usuarios')->group(function () {
        Route::get('/usuario', [AuthController::class, 'index'])->name('usuario.index');
        Route::put('/usuario/{id}/estado', [AuthController::class, 'actualizarEstado']);

       
    });
    // Route::get('/usuario', [AuthController::class, 'index'])->name('usuario.index');
    Route::get('/user', [AuthController::class, 'user'])->name('user.user');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/rol',[RoleController::class,'index']);
    Route::get('/producto',[ProductoController::class,'index'])->name('producto.index');
    Route::post('/producto', [ProductoController::class, 'store'])->name('producto.store');
    Route::put('/producto/{id}',[ProductoController::class,'update'])->name('producto.update');
    Route::delete('/producto/{id}',[ProductoController::class,'destroy']);
    Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::post('/cliente',[ClienteController::class,'store'])->name('cliente.store');
    Route::get('/cliente',[ClienteController::class,'index'])->name('cliente.index');
    Route::put('/cliente/{id}',[ClienteController::class,'update'])->name('cliente.update');
    Route::delete('/cliente/{id}',[ClienteController::class,'destroy'])->name('cliente.destroy');
    Route::get('/venta', [VentaController::class, 'index'])->name('venta.index');
    Route::get('/ventas/proximo-codigo', [VentaController::class, 'obtenerProximoCodigo']);
    Route::post('/venta', [VentaController::class, 'store'])->name('venta.store');



});
Route::get('/cors-check', function () {
    return response()->json(['message' => 'CORS funcionando']);
});
// Esta línea debe ir al final del archivo para capturar cualquier OPTIONS
Route::options('{any}', function () {
    return response()->json([], 204);
})->where('any', '.*');
