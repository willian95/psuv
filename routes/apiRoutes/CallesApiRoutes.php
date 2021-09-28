<?php

use App\Http\Controllers\Api\CallesController as Controller;
use Illuminate\Support\Facades\Route;
Route::get('/', [Controller::class,'index'])->name('api.calles.index');

