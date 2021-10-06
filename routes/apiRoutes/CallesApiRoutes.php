<?php

use App\Http\Controllers\Api\CallesController as Controller;
use Illuminate\Support\Facades\Route;
Route::get('/', [Controller::class,'index'])->name('api.calles.index');
Route::post('/', [Controller::class,'store'])->name('api.calles.store');
Route::put('/{id}', [Controller::class,'update'])->name('api.calles.update');
Route::delete('/{id}', [Controller::class,'destroy'])->name('api.calles.destroy');

