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
    ComunidadController,
    PersonalCaracterizacionController,
    CargoController,
    InstitucionController,
    MovimientoController,
    NivelEstructuraController,
};
use App\Http\Controllers\Api\RAAS\{
    UBCHController,
    JefeComunidadController,
    JefeCalleController,
    JefeFamiliaController
};
use App\Http\Controllers\Api\Reportes\{
    RaasController,
    ReporteCargaController,
    ListadoController
};

use App\Http\Controllers\Api\MetasUBCH\MetasUBCHController;
use App\Http\Controllers\Api\Votaciones\CuadernilloController;
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


//Roles Routes
Route::prefix('roles')->group(function () {
    require base_path('routes/apiRoutes/RolesApiRoutes.php');
});

//Permisos Routes
Route::prefix('permissions')->group(function () {
    require base_path('routes/apiRoutes/PermissionsApiRoutes.php');
});

//Users Routes
Route::prefix('users')->group(function () {
    require base_path('routes/apiRoutes/UsersApiRoutes.php');
});

//Calles Routes
Route::prefix('calles')->group(function () {
    require base_path('routes/apiRoutes/CallesApiRoutes.php');
});
//Comunidades Routes
Route::prefix('comunidades')->group(function () {
    require base_path('routes/apiRoutes/ComunidadesApiRoutes.php');
});
//Participacion instituciones Routes
Route::prefix('participacion-instituciones')->group(function () {
    require base_path('routes/apiRoutes/ParticipacionInstitucionesApiRoutes.php');
});
//Participacion movimientos Routes
Route::prefix('participacion-movimientos')->group(function () {
    require base_path('routes/apiRoutes/ParticipacionMovimientosApiRoutes.php');
});


//raas Routes
Route::prefix('raas')->group(function () {
    
    Route::prefix('jefe-calle')->group(function () {
        Route::get("/", [JefeCalleController::class, "index"])->name('api.jefe-calle.index');
        Route::get("/{cedula}", [JefeCalleController::class, "searchByCedulaField"])->name('api.jefe-calle.search-by-cedula')->middleware("web");
        Route::post("/", [JefeCalleController::class, "store"])->name('api.jefe-calle.store');
        Route::put("/{id}", [JefeCalleController::class, "update"])->name('api.jefe-calle.update');
        Route::delete("/{id}", [JefeCalleController::class, "delete"])->name('api.jefe-calle.delete');
    });
    
    Route::prefix('jefe-familia')->group(function () {
        Route::get("/", [JefeFamiliaController::class, "index"])->name('api.jefe-familia.index');
        Route::get("/{cedula}", [JefeFamiliaController::class, "searchByCedulaField"])->name('api.jefe-familia.search-by-cedula');
        Route::post("/", [JefeFamiliaController::class, "store"])->name('api.jefe-familia.store');
        Route::put("/{id}", [JefeFamiliaController::class, "update"])->name('api.jefe-familia.update');
        Route::delete("/{id}", [JefeFamiliaController::class, "delete"])->name('api.jefe-familia.delete');
    });
    
    Route::prefix('nucleo-familiar')->group(function () {
        Route::get("/", [JefeFamiliaController::class, "indexFamily"])->name('api.nucleo-familiar.index');
        Route::post("/", [JefeFamiliaController::class, "storeFamily"])->name('api.nucleo-familiar.store');
        Route::put("/{familyId}", [JefeFamiliaController::class, "updateFamily"])->name('api.nucleo-familiar.update');
        Route::delete("/{familyId}", [JefeFamiliaController::class, "deleteFamily"])->name('api.nucleo-familiar.delete');
    });
    
    Route::prefix('report')->group(function () {
        Route::get("/", [PersonalCaracterizacionController::class, "exportToExcel"])->name('api.raas.report');
        Route::get("/structure", [RaasController::class, "structure"])->name('api.raas.report.structure');
        Route::get("/voter_mobilization", [RaasController::class, "voterMobilization"])->name('api.raas.report.voter_mobilization');
    });

});

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::get('/verify',[AuthenticationController::class,'getAuthenticatedUser']);
});


Route::get("municipios", [MunicipioController::class, "all"]);

Route::get("parroquias/{municipio_id}", [ParroquiaController::class, "parroquiasByMunicipio"]);
Route::get("parroquias-busqueda/{municipio_nombre}", [ParroquiaController::class, "parroquiasByMunicipioNombre"]);

Route::get("comunidades/{parroquia}", [ComunidadController::class, "comunidadesByParroquia"]);

Route::get("centro-votacion/{parroquia_id}", [CentroVotacionController::class, "centroVotacionByParroquia"]);
Route::get("centro-votacion-busqueda/{parroquia_nombre}", [CentroVotacionController::class, "centroVotacionByParroquiaNombre"]);

Route::get("partidos-politicos", [PartidoPoliticoController::class, "all"])->name('api.partidos-politicos.index');

Route::get("movilizacion", [MovilizacionController::class, "all"])->name('api.movilizacion.index');
Route::get("cargos", [CargoController::class, "all"])->name('api.cargo.index');
Route::get("instituciones", [InstitucionController::class, "all"])->name('api.institucion.index');
Route::get("movimientos", [MovimientoController::class, "all"])->name('api.movimiento.index');
Route::get("niveles-estructura", [NivelEstructuraController::class, "all"])->name('api.niveles-estructura.index');
 

Route::post("raas/ubch/store", [UBCHController::class, "store"]);
Route::post("raas/ubch/update", [UBCHController::class, "update"]);
Route::post("raas/ubch/suspend", [UBCHController::class, "suspend"]);
Route::get("raas/ubch/fetch", [UBCHController::class, "fetch"])->middleware("web");
Route::get("raas/ubch/search", [UBCHController::class, "search"])->middleware("web");

Route::post("raas/jefe-comunidad/store", [JefeComunidadController::class, "store"]);
Route::get("raas/jefe-comunidad/fetch", [JefeComunidadController::class, "fetch"])->middleware("web");
Route::post("raas/jefe-comunidad/update", [JefeComunidadController::class, "update"]);
Route::post("raas/jefe-comunidad/suspend", [JefeComunidadController::class, "suspend"]);
Route::get("raas/jefe-comunidad/search", [JefeComunidadController::class, "search"]);
Route::post("raas/jefe-comunidad/search-jefe-ubch-by-cedula", [UBCHController::class, "jefeUbchByCedula"]);
Route::post("raas/jefe-comunidad/search-by-cedula", [JefeComunidadController::class, "searchByCedula"]);

Route::get("comunidad/fetch", [ComunidadController::class, "fetch"])->middleware("web");
Route::post("comunidad/store", [ComunidadController::class, "store"]);
Route::post("comunidad/update", [ComunidadController::class, "update"]);
Route::post("comunidad/delete", [ComunidadController::class, "delete"]);
Route::get("comunidad/search", [ComunidadController::class, "search"])->middleware("web");

Route::get("metas-ubch/download", [MetasUBCHController::class, "download"]);

Route::post("/reporte-carga/generate", [ReporteCargaController::class, "generate"]);
Route::get("/reporte-carga/download", [ReporteCargaController::class, "download"]);

Route::get("/listado-jefe/download", [ListadoController::class, "download"]);

Route::get("/cuadernillo", [CuadernilloController::class, "centrosVotacion"]);
Route::get("/cuadernillo/count-electores/{centro_votacion}", [CuadernilloController::class, "countElectores"]);
Route::post("/cuadernillo/store-export-job", [CuadernilloController::class, "storeExportJob"]);