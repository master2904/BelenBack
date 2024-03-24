<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConcursoController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\SucursalController;

Route::apiResource('/usuario', UsuarioController::class);
Route::apiResource('/cliente', ClienteController::class);
Route::apiResource('/sucursal',SucursalController::class);
Route::apiResource('/categoria',CategoriaController::class);

Route::apiResource('/producto',ProductoController::class);
Route::apiResource('/proveedor', ProveedorController::class);
Route::apiResource('/venta', VentaController::class);
Route::get('/categoria/sucursal/{id}', [CategoriaController::class,'listarSucursal']);
Route::get('/producto/categoria/{id}', [ProductoController::class,'listadoCategoria']);
Route::get('/producto/sucursal/{id}', [ProductoController::class,'listadoSucursal']);
Route::get('/producto/venta/{id}', [ProductoController::class,'listadoVenta']);

Route::apiResource('/relacion', 'App\Http\Controllers\RelacionController');
Route::apiResource('/detalle', 'App\Http\Controllers\DetalleController');
Route::apiResource('/tipo', 'App\Http\Controllers\TipoController');
Route::apiResource('/venta', 'App\Http\Controllers\VentaController');
Route::apiResource('/maquina', 'App\Http\Controllers\MaquinaController');
Route::apiResource('/proveedor', 'App\Http\Controllfgers\VendorController');
Route::apiResource('/problema', 'App\Http\Controllers\ProblemaController');
Route::post('/detalle/delete', [DetalleController::class,'delete']);
Route::get('/detalle/venta/{id}',[DetalleController::class,'lista_venta']);
Route::get('/venta/fecha/{id}/{fecha}',[VentaController::class,'fecha']);
Route::get('/categoria/buscar/{id}',[CategoriaController::class,'buscar']);
Route::get('/categoria/maxima/{id}',[CategoriaController::class,'buscar']);
// Route::post('/laboratorio/imagen', [LaboratorioController::class,'subirarchivo']);
Route::get('/concurso/activo/{id}',[ConcursoController::class,'activo']);
Route::post('/sucursal/imagen', [SucursalController::class,'imageUpload']);
Route::post('/usuario/imagen', [UsuarioController::class,'imageUpload']);
Route::post('/producto/imagen', [ProductoController::class,'imageUpload']);
Route::get('/producto/detalle/{id}', [ProductoController::class,'listado']);
Route::get('/venta/historial/{fecha}',[VentaController::class,'listar_fecha']);
Route::get('/usuario/descargar/{master}',[UsuarioController::class,'image']);
Route::post('/login', [UsuarioController::class,'authenticate']);
// Route::get("usuario/imagen/{nombre}",[UsuarioController::class,'descargar']);
Route::get("usuario/imagen/{imagen}",[UsuarioController::class,'image']);
Route::get("sucursal/imagen/{imagen}",[SucursalController::class,'image']);
Route::get("producto/imagen/{imagen}",[ProductoController::class,'image']);
// Route::get("laboratorio/imagen/{imagen}",[LaboratorioController::class,'image']);
Route::get("venta/meses/{id}",[VentaController::class,'r_meses']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('user','App\Http\Controllers\UsuarioController@getAuthenticatedUser');
});
Route::group(['middleware' => ['cors']], function () {
});
