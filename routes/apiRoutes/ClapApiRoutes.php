<?php

use App\Http\Controllers\Api\ClapController as Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index'])->name('api.clap.index');
Route::post('/', [Controller::class, 'store'])->name('api.clap.store');
Route::put('/{id}', [Controller::class, 'update'])->name('api.clap.update');
Route::delete('/{id}', [Controller::class, 'destroy'])->name('api.clap.destroy');
Route::get('/comunidad/{comunidad_id}', [Controller::class, 'getClapsByComunidad'])->name('api.clap.getClaps');
