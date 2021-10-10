<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\RAAS\UBCHController;
use App\Http\Controllers\CodigoCNEController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Listados\REPController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RAAS\{
    UBCHController as UBCHAPIController,
    JefeComunidadController as JefeComunidadAPIController
};

use App\Http\Controllers\Api\{
    ElectorController
};

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
})->name("login")->middleware("guest");

 Route::get('/home', function () {
    return view("dashboard");
 })->name("home")->middleware("auth");

 Route::view('/listado/rep', 'Listados.rep.rep')->name("listados.rep");
 Route::post("/listado/amount/rep", [REPController::class,'getMunicipioAmount']);
 Route::get("/listado/importar/rep", [REPController::class,'download']);
 Route::post("/listado/rep/store-export-job", [REPController::class,'storeExportJob']);

 Route::get('/email-verify/{token}', [AuthenticationController::class,'verifyEmailToken']);

Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"]);

Route::post("raas/ubch/search-by-cedula", [UBCHAPIController::class, "searchByCedula"]);

Route::post("raas/jefe-comunidad/search-jefe-ubch-by-cedula", [UBCHAPIController::class, "jefeUbchByCedula"]);
Route::post("raas/jefe-comunidad/search-by-cedula", [JefeComunidadAPIController::class, "searchByCedula"]);
Route::post("raas/jefe-comunidad/search-by-cedula-field", [JefeComunidadAPIController::class, "searchByCedulaField"]);

Route::get("elector/search-by-cedula", [ElectorController::class, "searchByCedula"])->name('api.elector.search.by.cedula');

Route::view("/metas-ubch", "metasUBCH.metas");

Route::view("/reporte-carga", "reporteCarga.reporte");

//Auth routes
Route::group(['middleware' => ['auth']], function() {

    //Admin modules
    Route::group(['prefix' => 'admin'], function () {

        Route::get('/calles', function () {
            return view('admin.calles.view');
        });

        Route::get('/comunidad', function () {
            return view('comunidad.index');
        });
    
    });

    //Raas modules
    Route::group(['prefix' => 'raas'], function () {

        Route::view('ubch', 'RAAS.jefeUbch.ubch')->name("raas.ubch");
    
        Route::view('jefeComunidad', 'RAAS.jefeComunidad.jefeComunidad')->name("raas.jefe-comunidad");
       
        Route::view('jefeCalle', 'RAAS.jefeCalle.view')->name("raas.jefe-calle");
       
        Route::view('jefeFamilia', 'RAAS.jefeFamilia.view')->name("raas.jefe-familia");
    
        Route::view('reportes/estructura', 'reports.raas.structure');
    
    });
});
