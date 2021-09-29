<?php

use App\Http\Controllers\Api\Auth\{AuthenticationController, PasswordResetController};
use App\Http\Controllers\Api\{
    RoleController, 
    MunicipioController, 
    ParroquiaController, 
    CentroVotacionController, 
    PartidoPoliticoController, 
    MovilizacionController, 
    ElectorController, 
    ComunidadController

};
use App\Http\Controllers\Api\RAAS\{
    UBCHController,
    JefeComunidadController,
    JefeCalleController,
    JefeFamiliaController
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

//Calles Routes
Route::prefix('calles')->group(function () {
    require base_path('routes/apiRoutes/CallesApiRoutes.php');
});

//Calles Routes
Route::prefix('raas/jefe-calle')->group(function () {
    Route::get("/", [JefeCalleController::class, "index"])->name('api.jefe-calle.index');
    Route::get("/{cedula}", [JefeCalleController::class, "searchByCedulaField"])->name('api.jefe-calle.search-by-cedula');
    Route::post("/", [JefeCalleController::class, "store"])->name('api.jefe-calle.store');
    Route::put("/{id}", [JefeCalleController::class, "update"])->name('api.jefe-calle.update');
    Route::delete("/{id}", [JefeCalleController::class, "delete"])->name('api.jefe-calle.delete');
});
Route::prefix('raas/jefe-familia')->group(function () {
    Route::get("/", [JefeFamiliaController::class, "index"])->name('api.jefe-familia.index');
    Route::get("/{cedula}", [JefeFamiliaController::class, "searchByCedulaField"])->name('api.jefe-familia.search-by-cedula');
    Route::post("/", [JefeFamiliaController::class, "store"])->name('api.jefe-familia.store');
    Route::put("/{id}", [JefeFamiliaController::class, "update"])->name('api.jefe-familia.update');
});

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('/verify',[AuthenticationController::class,'getAuthenticatedUser']);
});


Route::get("municipios", [MunicipioController::class, "all"]);

Route::get("parroquias/{municipio_id}", [ParroquiaController::class, "parroquiasByMunicipio"]);

Route::get("comunidades/{parroquia}", [ComunidadController::class, "comunidadesByParroquia"]);

Route::get("centro-votacion/{parroquia_id}", [CentroVotacionController::class, "centroVotacionByParroquia"]);

Route::get("partidos-politicos", [PartidoPoliticoController::class, "all"])->name('api.partidos-politicos.index');

Route::get("movilizacion", [MovilizacionController::class, "all"])->name('api.movilizacion.index');

Route::post("raas/ubch/search-by-cedula", [ElectorController::class, "searchByCedula"]);
Route::post("raas/ubch/store", [UBCHController::class, "store"]);
Route::post("raas/ubch/update", [UBCHController::class, "update"]);
Route::post("raas/ubch/suspend", [UBCHController::class, "suspend"]);
Route::get("raas/ubch/fetch", [UBCHController::class, "fetch"]);

Route::post("raas/jefe-comunidad/search-jefe-ubch-by-cedula", [UBCHController::class, "jefeUbchByCedula"]);
Route::post("raas/jefe-comunidad/search-by-cedula", [ElectorController::class, "searchByCedula"]);
Route::post("raas/jefe-comunidad/store", [JefeComunidadController::class, "store"]);
Route::get("raas/jefe-comunidad/fetch", [JefeComunidadController::class, "fetch"]);
Route::post("raas/jefe-comunidad/update", [JefeComunidadController::class, "update"]);
Route::post("raas/jefe-comunidad/suspend", [JefeComunidadController::class, "suspend"]);


Route::get("raas/jefe-comunidad/search-by-cedula", [JefeComunidadController::class, "searchByCedulaField"])->name('api.jefe-comunidad.search.by.cedula');


