<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('editora', 'App\Http\Controllers\EditoraController');
Route::apiResource('personagem', 'App\Http\Controllers\PersonagemController');
Route::apiResource('status', 'App\Http\Controllers\StatusController');
Route::apiResource('tipo-serie', 'App\Http\Controllers\TipoSerieController');
Route::apiResource('hq', 'App\Http\Controllers\HqController');
Route::apiResource('hq-editora', 'App\Http\Controllers\HqEditoraController');
Route::apiResource('hq-personagem', 'App\Http\Controllers\HqPersonagemController');

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');