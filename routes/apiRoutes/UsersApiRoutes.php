<?php

use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;
// $middle=['jwt.verify.role:admin|office|finance|approves'];
$middle=["jwt.verify"];
Route::group(['middleware' => $middle], function() {//
    Route::get('/', [UsersController::class,'index']);
    Route::get('/{id}', [UsersController::class,'show']);
    Route::post('/', [UsersController::class,'store']);
    Route::put('/{id}', [UsersController::class,'update']);
    Route::delete('/{id}', [UsersController::class,'destroy']);
});
