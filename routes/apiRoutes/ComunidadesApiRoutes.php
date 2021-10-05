<?php

use App\Http\Controllers\Api\ComunidadesController as Controller;
use Illuminate\Support\Facades\Route;
Route::get('/', [Controller::class,'index'])->name('api.comunidades.index');
