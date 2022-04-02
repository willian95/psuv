<?php

use App\Http\Controllers\Api\Clap\EnlaceMunicipalController;
use App\Http\Controllers\Api\Clap\JefeCalleClapController;
use App\Http\Controllers\Api\Clap\JefeClapController;
use App\Http\Controllers\Api\Clap\JefeComunidadClapController;
use App\Http\Controllers\Api\Clap\JefeFamiliaController;
use App\Models\JefeFamilia;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'enlace-municipal'], function () {
    Route::post('search-by-cedula', [EnlaceMunicipalController::class, 'searchByCedula']);
    Route::post('/', [EnlaceMunicipalController::class, 'store']);
    Route::get('index', [EnlaceMunicipalController::class, 'fetch']);
    Route::delete('/{id}', [EnlaceMunicipalController::class, 'delete']);
    Route::put('/{id}', [EnlaceMunicipalController::class, 'update']);
    Route::get('all', [EnlaceMunicipalController::class, 'fetchAll']);
});

Route::group(['prefix' => 'jefe-clap'], function () {
    Route::post('search-by-cedula', [JefeClapController::class, 'searchByCedula']);
    Route::post('/', [JefeClapController::class, 'store']);
    Route::get('index', [JefeClapController::class, 'fetch']);
    Route::delete('/{id}', [JefeClapController::class, 'delete']);
    Route::put('/{id}', [JefeClapController::class, 'update']);
    Route::post('search-jefe-by-cedula', [JefeClapController::class, 'searchJefeClapByCedula']);
});

Route::group(['prefix' => 'jefe-comunidad-clap'], function () {
    Route::post('search-by-cedula', [JefeComunidadClapController::class, 'searchByCedula']);
    Route::post('/', [JefeComunidadClapController::class, 'store']);
    Route::get('index', [JefeComunidadClapController::class, 'fetch']);
    Route::delete('/{id}', [JefeComunidadClapController::class, 'delete']);
    Route::put('/{id}', [JefeComunidadClapController::class, 'update']);
    Route::post('search-jefe-by-cedula', [JefeComunidadClapController::class, 'searchJefeComunidadClapByCedula']);
});

Route::group(['prefix' => 'jefe-calle-clap'], function () {
    Route::post('search-by-cedula', [JefeCalleClapController::class, 'searchByCedula']);
    Route::post('/', [JefeCalleClapController::class, 'store']);
    Route::get('index', [JefeCalleClapController::class, 'fetch']);
    Route::delete('/{id}', [JefeCalleClapController::class, 'delete']);
    Route::put('/{id}', [JefeCalleClapController::class, 'update']);
    Route::post('search-jefe-by-cedula', [JefeCalleClapController::class, 'searchJefeCalleClapByCedula']);
});


Route::group(['prefix' => 'jefe-familia-clap'], function () {
    Route::post('search-by-cedula', [JefeFamiliaController::class, 'searchByCedula']);
    Route::post('/', [JefeFamiliaController::class, 'store']);
    Route::get('index', [JefeFamiliaController::class, 'fetch']);
    Route::delete('/{id}', [JefeFamiliaController::class, 'delete']);
    Route::put('/{id}', [JefeFamiliaController::class, 'update']);
    Route::post('search-jefe-by-cedula', [JefeFamiliaController::class, 'searchJefeCalleClapByCedula']);
    Route::get("estatus-personal", [JefeFamiliaController::class, 'getEstatusPersonal']);
    Route::get("/get-casas/{id}", [JefeFamiliaController::class, 'getCasasByCalle']);
});


