<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlotController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

//plot
Route::get('/map', [App\Http\Controllers\PlotController::class, 'mapview'])->name('map');
Route::get('/getplots', [App\Http\Controllers\PlotController::class, 'getPlots'])->name('plots');
Route::get('/getgrave/{id}', [App\Http\Controllers\PlotController::class, 'getGrave'])->name('grave');
Route::put('/graveUpdate/{id}', [App\Http\Controllers\PlotController::class, 'graveUpdate'])->name('grave.update');
Route::resource('plots',PlotController::class);


//users
Route::get('/users', [App\Http\Controllers\UserController::class, 'users'])->name('users');
Route::get('/getuser/{id}', [App\Http\Controllers\UserController::class, 'getuser'])->name('user.get');
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'createAdmin'])->name('users.create');
Route::get('/clientlist', [App\Http\Controllers\UserController::class, 'clientlist'])->name('users.clients');

//clients
Route::get('/clients', [App\Http\Controllers\UserController::class, 'index'])->name('client');
Route::get('/clients/create', [App\Http\Controllers\UserController::class, 'create'])->name('client.create');

// Route::get('/logout', function(){
//     Auth::logout();
//     return Redirect::to('/');
//  });