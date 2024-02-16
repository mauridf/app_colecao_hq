<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/editoras', function() {
    return view('app.editoras');
})->name('editoras')->middleware('auth');

Route::get('/personagens', function() {
    return view('app.personagens');
})->name('personagens')->middleware('auth');

Route::get('/status', function() {
    return view('app.status');
})->name('status')->middleware('auth');

Route::get('/tiposeries', function() {
    return view('app.tiposeries');
})->name('tiposeries')->middleware('auth');