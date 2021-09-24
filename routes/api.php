<?php

use App\Http\Controllers\Api\Auth\{AuthenticationController, PasswordResetController};
use App\Http\Controllers\Api\{
    BankController, CourierTypeController, ManagerTypeController, RoleController
};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Postmark\PostmarkClient;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('auth')->group(function(){
    Route::post('/login/{role}',[AuthenticationController::class,'authentication']);
    Route::post('/recovery-password/{role}',[PasswordResetController::class,'verifyEmail']);
    Route::post('/new-password',[PasswordResetController::class,'resetPassword']);
    Route::get('/logout',[AuthenticationController::class,'logout'])->middleware('api');
});

// Data routes
Route::get('/roles', [RoleController::class,'index']);

//Users Routes
Route::prefix('users')->group(function () {
    require base_path('routes/apiRoutes/UsersApiRoutes.php');
});

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('/verify',[AuthenticationController::class,'getAuthenticatedUser']);
});
