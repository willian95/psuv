<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\RAAS\UBCHController;
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
    return view('login');
})->name("login");


 Route::get('/dashboard', function () {
    return view("dashboard");
 })->name("home");

 Route::view('/raas/ubch', 'RAAS.ubch')->name("raas.ubch");

 Route::view('/raas/jefeComunidad', 'RAAS.jefeComunidad')->name("raas.jefe-comunidad");

 Route::view('/raas/jefeCalle', 'RAAS.jefeCalle.view')->name("raas.jefe-calle");

 Route::view('/raas/jefeFamilia', 'RAAS.jefeFamilia.view')->name("raas.jefe-familia");

 Route::get('/email-verify/{token}', [AuthenticationController::class,'verifyEmailToken']);
