<?php

use App\Http\Controllers\Api\Clap\EnlaceMunicipalController;
use App\Http\Controllers\Api\Clap\JefeClapController;
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
});
