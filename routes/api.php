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

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::apiResource('editora', 'App\Http\Controllers\EditoraController');
    Route::apiResource('personagem', 'App\Http\Controllers\PersonagemController');
    Route::apiResource('status', 'App\Http\Controllers\StatusController');
    Route::apiResource('tiposerie', 'App\Http\Controllers\TipoSerieController');
    Route::apiResource('hq', 'App\Http\Controllers\HqController');
    Route::apiResource('hq-editora', 'App\Http\Controllers\HqEditoraController');
    Route::apiResource('hq-personagem', 'App\Http\Controllers\HqPersonagemController');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');