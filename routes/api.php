<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Ruta de reserva para manejar solicitudes no válidas
Route::fallback(function(){
    return response()->json([
        'status' => false,
        'code' => 404,
        'message' => 'La ruta solicitada no es válida.'
    ]);
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    //Articulos
    Route::resource('articulos/lista', 'Api\V1\ArticuloController')->only(['index']);
    Route::get('articulos', 'Api\V1\ArticuloController@show');
    Route::post('articulos/carga', 'Api\V1\ArticuloController@store');
    Route::put('articulos/editar', 'Api\V1\ArticuloController@update');
    Route::delete('articulos/borrar', 'Api\V1\ArticuloController@destroy');

    //Deposito
    Route::resource('deposito/lista', 'Api\V1\DepositoController')->only(['index']);
    Route::get('deposito', 'Api\V1\DepositoController@show');
    Route::post('deposito/carga', 'Api\V1\DepositoController@store');
    Route::put('deposito/editar', 'Api\V1\DepositoController@update');
    Route::delete('deposito/borrar', 'Api\V1\DepositoController@destroy');

    //Tipo Articulo
    Route::resource('tipoArticulo/lista', 'Api\V1\TipoArticuloController')->only(['index']);
    Route::get('tipoArticulo', 'Api\V1\TipoArticuloController@show');
    Route::post('tipoArticulo/carga', 'Api\V1\TipoArticuloController@store');
    Route::put('tipoArticulo/editar', 'Api\V1\TipoArticuloController@update');
    Route::delete('tipoArticulo/borrar', 'Api\V1\TipoArticuloController@destroy');

});


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
        Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });
});