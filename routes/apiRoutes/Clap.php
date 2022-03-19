<?php

use App\Http\Controllers\Api\Clap\EnlaceMunicipalController;
use Illuminate\Support\Facades\Route;

Route::post('enlace-municipal/search-by-cedula', [EnlaceMunicipalController::class, 'searchByCedula']);
Route::post('/enlace-municipal', [EnlaceMunicipalController::class, 'store']);
Route::get('/enlace-municipal/index', [EnlaceMunicipalController::class, 'fetch']);
Route::delete('/enlace-municipal/{id}', [EnlaceMunicipalController::class, 'delete']);
Route::put('/enlace-municipal/{id}', [EnlaceMunicipalController::class, 'update']);
