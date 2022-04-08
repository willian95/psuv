<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CandidatoController;
use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\CentroVotacionController;
use App\Http\Controllers\Api\CierreMesa\{
    CandidatoController as CierreCandidatoController,
};
use App\Http\Controllers\Api\ComisionTrabajoController;
use App\Http\Controllers\Api\ComunidadController;
use App\Http\Controllers\Api\EstadoController;
use App\Http\Controllers\Api\InstitucionController;
use App\Http\Controllers\Api\MesaController;
use App\Http\Controllers\Api\MetasUBCH\MetasUBCHController;
use App\Http\Controllers\Api\MovilizacionController;
use App\Http\Controllers\Api\MovimientoController;
use App\Http\Controllers\Api\MunicipioController;
use App\Http\Controllers\Api\NivelEstructuraController;
use App\Http\Controllers\Api\ParroquiaController;
use App\Http\Controllers\Api\PartidoPoliticoController;
use App\Http\Controllers\Api\PersonalCaracterizacionController;
use App\Http\Controllers\Api\PersonalComandoMunicipalController;
use App\Http\Controllers\Api\PersonalComandoParroquialController;
use App\Http\Controllers\Api\PersonalComandoRegionalController;
use App\Http\Controllers\Api\PersonalEnlaceTerritorialController;
use App\Http\Controllers\Api\PersonalPuntoRojoController;
use App\Http\Controllers\Api\RAAS\JefeCalleController;
use App\Http\Controllers\Api\RAAS\JefeComunidadController;
use App\Http\Controllers\Api\RAAS\JefeFamiliaController;
use App\Http\Controllers\Api\RAAS\UBCHController;
use App\Http\Controllers\Api\Reportes\InstitucionController as InstitucionReportController;
use App\Http\Controllers\Api\Reportes\ListadoController;
use App\Http\Controllers\Api\Reportes\MovimientoReportController;
use App\Http\Controllers\Api\Reportes\RaasController;
use App\Http\Controllers\Api\Reportes\ReporteCargaController;
use App\Http\Controllers\Api\ResponsabilidadComandoController;
use App\Http\Controllers\Api\SalaTecnica\{
    AsociarPersonalController
};
use App\Http\Controllers\Api\TestigoMesaController;
use App\Http\Controllers\Api\Votaciones\CentroVotacionController as VotacionesCentroVotacionController;
use App\Http\Controllers\Api\Votaciones\CuadernilloController;
use App\Http\Controllers\Api\Votaciones\GestionarParticipacionController;
use App\Http\Controllers\Api\Votaciones\GestionarVotosController;
use App\Http\Controllers\{
    DashboardController
};
use App\Http\Controllers\Api\OrderOperacionesController;
use Illuminate\Support\Facades\Route;

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
Route::prefix('auth')->group(function () {
    Route::post('/login/{role}', [AuthenticationController::class, 'authentication']);
    Route::post('/recovery-password/{role}', [PasswordResetController::class, 'verifyEmail']);
    Route::post('/new-password', [PasswordResetController::class, 'resetPassword']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware('api');
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

//Clap Routes
Route::prefix('clap')->group(function () {
    require base_path('routes/apiRoutes/ClapApiRoutes.php');
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

//Participacion movimientos Routes
Route::prefix('clap')->group(function () {
    require base_path('routes/apiRoutes/Clap.php');
});

//raas Routes
Route::prefix('raas')->group(function () {
    Route::post('ubch/search-by-cedula', [UBCHController::class, 'searchByCedula']);

    Route::prefix('jefe-calle')->group(function () {
        Route::get('/', [JefeCalleController::class, 'index'])->name('api.jefe-calle.index');
        Route::get('/{cedula}', [JefeCalleController::class, 'searchByCedulaField'])->name('api.jefe-calle.search-by-cedula')->middleware('web');
        Route::post('/', [JefeCalleController::class, 'store'])->name('api.jefe-calle.store');
        Route::put('/{id}', [JefeCalleController::class, 'update'])->name('api.jefe-calle.update');
        Route::delete('/{id}', [JefeCalleController::class, 'delete'])->name('api.jefe-calle.delete');
    });

    Route::prefix('jefe-familia')->group(function () {
        Route::get('/', [JefeFamiliaController::class, 'index'])->name('api.jefe-familia.index');
        Route::get('/{cedula}', [JefeFamiliaController::class, 'searchByCedulaField'])->name('api.jefe-familia.search-by-cedula');
        Route::post('/', [JefeFamiliaController::class, 'store'])->name('api.jefe-familia.store');
        Route::put('/{id}', [JefeFamiliaController::class, 'update'])->name('api.jefe-familia.update');
        Route::delete('/{id}', [JefeFamiliaController::class, 'delete'])->name('api.jefe-familia.delete');
    });

    Route::prefix('nucleo-familiar')->group(function () {
        Route::get('/', [JefeFamiliaController::class, 'indexFamily'])->name('api.nucleo-familiar.index');
        Route::post('/', [JefeFamiliaController::class, 'storeFamily'])->name('api.nucleo-familiar.store');
        Route::put('/{familyId}', [JefeFamiliaController::class, 'updateFamily'])->name('api.nucleo-familiar.update');
        Route::delete('/{familyId}', [JefeFamiliaController::class, 'deleteFamily'])->name('api.nucleo-familiar.delete');
    });

    Route::prefix('report')->group(function () {
        Route::get('/', [PersonalCaracterizacionController::class, 'exportToExcel'])->name('api.raas.report');
        Route::get('/structure', [RaasController::class, 'structure'])->name('api.raas.report.structure');
        Route::get('/voter_mobilization', [RaasController::class, 'voterMobilization'])->name('api.raas.report.voter_mobilization');
        Route::get('/institutions/list', [InstitucionReportController::class, 'institutionList'])->name('api.institutions.report.list');
    });
});

Route::prefix('report')->group(function () {
    Route::get('/institutions/list', [InstitucionReportController::class, 'institutionList'])->name('api.institutions.report.list');
    Route::get('/movements/list', [MovimientoReportController::class, 'movementList'])->name('api.movements.report.list');
    Route::get('/comandos/regional', [PersonalComandoRegionalController::class, 'excel']);
    Route::get('/comandos/municipal', [PersonalComandoMunicipalController::class, 'excel']);
    Route::get('/comandos/parroquial', [PersonalComandoParroquialController::class, 'excel']);
    Route::get('/comandos/enlace', [PersonalEnlaceTerritorialController::class, 'excel']);
    Route::get('/candidatos', [CandidatoController::class, 'excel']);
    Route::get('/cierre/candidatos', [CandidatoController::class, 'cierreCandidato']);
    Route::get('/cierre/candidatos/detalle', [CandidatoController::class, 'cierreCandidatoDetalle']);
    Route::get('/cierre/candidatos-partido', [CandidatoController::class, 'cierrePartido']);
    Route::get('/cierre/candidatos-partido/detalle', [CandidatoController::class, 'cierrePartidoDetalle']);
});

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/verify', [AuthenticationController::class, 'getAuthenticatedUser']);
});

Route::get('estados', [EstadoController::class, 'all']);

Route::get('municipios', [MunicipioController::class, 'all']);
Route::get('municipios/{estado_id}', [MunicipioController::class, 'estado']);

Route::get('parroquias/{municipio_id}', [ParroquiaController::class, 'parroquiasByMunicipio']);
Route::get('parroquias-busqueda/{municipio_nombre}', [ParroquiaController::class, 'parroquiasByMunicipioNombre']);

Route::get('comunidades/{parroquia}', [ComunidadController::class, 'comunidadesByParroquia']);

Route::get('centro-votacion/', [CentroVotacionController::class, 'index'])->name('api.centros.votacion.index');
Route::get('centro-votacion/{parroquia_id}', [CentroVotacionController::class, 'centroVotacionByParroquia']);
Route::get('centro-votacion-busqueda/{parroquia_nombre}', [CentroVotacionController::class, 'centroVotacionByParroquiaNombre']);

Route::get('partidos-politicos', [PartidoPoliticoController::class, 'all'])->name('api.partidos-politicos.index');

Route::get('movilizacion', [MovilizacionController::class, 'all'])->name('api.movilizacion.index');
Route::get('cargos', [CargoController::class, 'all'])->name('api.cargo.index');
Route::get('instituciones', [InstitucionController::class, 'all'])->name('api.institucion.index');
Route::get('movimientos', [MovimientoController::class, 'all'])->name('api.movimiento.index');
Route::get('niveles-estructura', [NivelEstructuraController::class, 'all'])->name('api.niveles-estructura.index');
Route::get('comisiones-trabajos', [ComisionTrabajoController::class, 'all'])->name('api.comision.trabajo.index');
Route::get('responsabilidades-comandos', [ResponsabilidadComandoController::class, 'all'])->name('api.responsabilidad.comando.index');

Route::post('raas/ubch/store', [UBCHController::class, 'store']);
Route::post('raas/ubch/update', [UBCHController::class, 'update']);
Route::post('raas/ubch/suspend', [UBCHController::class, 'suspend']);
Route::get('raas/ubch/fetch', [UBCHController::class, 'fetch'])->middleware('web');
Route::get('raas/ubch/search', [UBCHController::class, 'search'])->middleware('web');

Route::post('raas/jefe-comunidad/store', [JefeComunidadController::class, 'store']);
Route::get('raas/jefe-comunidad/fetch', [JefeComunidadController::class, 'fetch'])->middleware('web');
Route::post('raas/jefe-comunidad/update', [JefeComunidadController::class, 'update']);
Route::post('raas/jefe-comunidad/suspend', [JefeComunidadController::class, 'suspend']);
Route::get('raas/jefe-comunidad/search', [JefeComunidadController::class, 'search']);
Route::post('raas/jefe-comunidad/search-jefe-ubch-by-cedula', [UBCHController::class, 'jefeUbchByCedula']);
Route::post('raas/jefe-comunidad/search-by-cedula', [JefeComunidadController::class, 'searchByCedula']);

Route::get('comunidad/fetch', [ComunidadController::class, 'fetch'])->middleware('web');
Route::post('comunidad/store', [ComunidadController::class, 'store']);
Route::post('comunidad/update', [ComunidadController::class, 'update']);
Route::post('comunidad/delete', [ComunidadController::class, 'delete']);
Route::get('comunidad/search', [ComunidadController::class, 'search'])->middleware('web');

Route::get('metas-ubch/download', [MetasUBCHController::class, 'download']);

Route::post('/reporte-carga/generate', [ReporteCargaController::class, 'generate']);
Route::get('/reporte-carga/download', [ReporteCargaController::class, 'download']);

Route::get('/listado-jefe/download', [ListadoController::class, 'download']);
Route::get('/listado-estructura-clap/download', [ListadoController::class, 'downloadEstructuraClap']);
Route::get('/listado-censo-poblacional/download', [ListadoController::class, 'downloadCensoPoblacional']);

Route::get('/cuadernillo', [CuadernilloController::class, 'centrosVotacion']);
Route::get('/cuadernillo/count-electores/{centro_votacion}', [CuadernilloController::class, 'countElectores']);
Route::get('/cuadernillo/count-electores-ubch/{centro_votacion}', [CuadernilloController::class, 'countElectoresUbch']);
Route::post('/cuadernillo/store-export-job', [CuadernilloController::class, 'storeExportJob']);

Route::get('/gestionar-votos/get-centros', [GestionarVotosController::class, 'getCentrosVotacion']);
Route::get('/gestionar-votos/search-centros', [GestionarVotosController::class, 'searchCentrosVotacion']);

Route::get('sala-tecnica/get-personal', [AsociarPersonalController::class, 'getPersonal']);
Route::post('sala-tecnica/store-personal', [AsociarPersonalController::class, 'storePersonal']);
Route::post('sala-tecnica/update-personal', [AsociarPersonalController::class, 'updatePersonal']);
Route::post('sala-tecnica/delete-personal', [AsociarPersonalController::class, 'deletePersonal']);
Route::get('sala-tecnica/search-personal', [AsociarPersonalController::class, 'searchPersonal']);

//Comandos regionales
Route::prefix('comandos')->group(function () {
    Route::prefix('regionales')->group(function () {
        Route::get('/', [PersonalComandoRegionalController::class, 'index'])->name('api.comando.regional.index');
        Route::post('/', [PersonalComandoRegionalController::class, 'store'])->name('api.comando.regional.store');
        Route::put('/{id}', [PersonalComandoRegionalController::class, 'update'])->name('api.comando.regional.update');
        Route::delete('/{id}', [PersonalComandoRegionalController::class, 'delete'])->name('api.comando.regional.delete');
    });
    Route::prefix('municipales')->group(function () {
        Route::get('/', [PersonalComandoMunicipalController::class, 'index'])->name('api.comando.municipal.index');
        Route::post('/', [PersonalComandoMunicipalController::class, 'store'])->name('api.comando.municipal.store');
        Route::put('/{id}', [PersonalComandoMunicipalController::class, 'update'])->name('api.comando.municipal.update');
        Route::delete('/{id}', [PersonalComandoMunicipalController::class, 'delete'])->name('api.comando.municipal.delete');
    });
    Route::prefix('parroquiales')->group(function () {
        Route::get('/', [PersonalComandoParroquialController::class, 'index'])->name('api.comando.parroquial.index');
        Route::post('/', [PersonalComandoParroquialController::class, 'store'])->name('api.comando.parroquial.store');
        Route::put('/{id}', [PersonalComandoParroquialController::class, 'update'])->name('api.comando.parroquial.update');
        Route::delete('/{id}', [PersonalComandoParroquialController::class, 'delete'])->name('api.comando.parroquial.delete');
    });
    Route::prefix('enlaces')->group(function () {
        Route::get('/', [PersonalEnlaceTerritorialController::class, 'index'])->name('api.comando.enlace.index');
        Route::post('/', [PersonalEnlaceTerritorialController::class, 'store'])->name('api.comando.enlace.store');
        Route::put('/{id}', [PersonalEnlaceTerritorialController::class, 'update'])->name('api.comando.enlace.update');
        Route::delete('/{id}', [PersonalEnlaceTerritorialController::class, 'delete'])->name('api.comando.enlace.delete');
    });
});

Route::prefix('candidatos')->group(function () {
    Route::get('/', [CandidatoController::class, 'index'])->name('api.candidatos.index');
    Route::post('/', [CandidatoController::class, 'store'])->name('api.candidatos.store');
    Route::put('/{id}', [CandidatoController::class, 'update'])->name('api.candidatos.update');
    Route::delete('/{id}', [CandidatoController::class, 'delete'])->name('api.candidatos.delete');
});

Route::prefix('mesas')->group(function () {
    Route::get('/', [MesaController::class, 'index'])->name('api.mesas.index');
    Route::post('/', [MesaController::class, 'store'])->name('api.mesas.store');
    Route::put('/{id}', [MesaController::class, 'update'])->name('api.mesas.update');
    Route::delete('/{id}', [MesaController::class, 'delete'])->name('api.mesas.delete');
});

Route::prefix('testigos')->group(function () {
    Route::get('/', [TestigoMesaController::class, 'index'])->name('api.testigos.index');
    Route::post('/', [TestigoMesaController::class, 'store'])->name('api.testigos.store');
    Route::put('/{id}', [TestigoMesaController::class, 'update'])->name('api.testigos.update');
    Route::delete('/{id}', [TestigoMesaController::class, 'delete'])->name('api.testigos.delete');
});

Route::prefix('personal-punto-rojo')->group(function () {
    Route::get('/', [PersonalPuntoRojoController::class, 'index'])->name('api.puntorojo.index');
    Route::post('/', [PersonalPuntoRojoController::class, 'store'])->name('api.puntorojo.store');
    Route::put('/{id}', [PersonalPuntoRojoController::class, 'update'])->name('api.puntorojo.update');
    Route::delete('/{id}', [PersonalPuntoRojoController::class, 'delete'])->name('api.puntorojo.delete');
});

Route::prefix('candidato-actualizar-imagen')->group(function () {
    Route::post('/{id}', [CandidatoController::class, 'updateImage'])->name('api.candidato.update.image');
});

Route::get('/votaciones/centro-votacion/get-centros', [VotacionesCentroVotacionController::class, 'getCentrosVotacion']);
Route::get('/votaciones/centro-votacion/search-centros', [VotacionesCentroVotacionController::class, 'searchCentrosVotacion']);
Route::post('/votaciones/centro-votacion/search-by-codigo-cuadernillo', [VotacionesCentroVotacionController::class, 'searchByCodigoCuadernillo']);
Route::post('/votaciones/centro-votacion/search-by-cedula', [VotacionesCentroVotacionController::class, 'searchByCedula']);
Route::post('/votaciones/centro-votacion/update-voto', [VotacionesCentroVotacionController::class, 'updateEjercioVoto']);
Route::post('/votaciones/centro-votacion/update-voto-instituciones', [VotacionesCentroVotacionController::class, 'updateEjercioVotoInstitucion']);
Route::get('/votaciones/centro-votacion/get-votantes', [VotacionesCentroVotacionController::class, 'getVotantesByCentroVotacion']);
Route::get('/votaciones/centro-votacion/search-votantes', [VotacionesCentroVotacionController::class, 'searchVotantesByCentroVotacion']);
Route::get('/votaciones/centro-votacion/get-personal-punto-rojo', [VotacionesCentroVotacionController::class, 'getPersonalPuntoRojo']);
Route::get('/votaciones/centro-votacion/search-personal-punto-rojo', [VotacionesCentroVotacionController::class, 'searchPersonalPuntoRojo']);
Route::get('/votaciones/centro-votacion/export-personal-punto-rojo/{centroVotacionId}', [VotacionesCentroVotacionController::class, 'exportPersonalPuntoRojo']);
Route::post('/votaciones/centro-votacion/delete-voto', [VotacionesCentroVotacionController::class, 'deleteVoto']);

Route::get('/votaciones/centro-votacion/mesa/{centro_votacion_id}', [GestionarParticipacionController::class, 'getMesas']);
Route::get('/votaciones/centro-votacion/mesa/participaciones/get-participaciones', [GestionarParticipacionController::class, 'getParticipaciones']);
Route::get('/votaciones/centro-votacion/mesa/participaciones/search-mesa', [GestionarParticipacionController::class, 'searchMesaNombre']);
Route::post('/votaciones/centro-votacion/mesa/store', [GestionarParticipacionController::class, 'store']);
Route::post('/votaciones/centro-votacion/mesa/delete', [GestionarParticipacionController::class, 'delete']);

Route::get('/cierre-mesa/candidato', [CierreCandidatoController::class, 'getMesasCerradas']);
Route::get('/cierre-mesa/candidato/get-mesas/{centro_votacion_id}', [CierreCandidatoController::class, 'getMesasByCentroVotacion']);
Route::get('/cierre-mesa/candidato/get-candidatos', [CierreCandidatoController::class, 'getCandidatos']);
Route::get('/cierre-mesa/candidato/get-candidatos-partido', [CierreCandidatoController::class, 'getCandidatosPartidoPolitico']);
Route::get('/cierre-mesa/candidato/get-mesas-cerradas', [CierreCandidatoController::class, 'getMesasCerradas']);
Route::get('/cierre-mesa/candidato/get-resultadods/{mesa_id}', [CierreCandidatoController::class, 'getResultados']);
Route::get('/cierre-mesa/candidato/search-centro-votacion', [CierreCandidatoController::class, 'searchCentroVotacion']);
Route::get('/cierre-mesa/candidato/search-candidato', [CierreCandidatoController::class, 'searchCandidato']);
Route::post('/cierre-mesa/candidato/mesa/update', [CierreCandidatoController::class, 'updateCierreMesa']);
Route::post('/cierre-mesa/candidato/store-results', [CierreCandidatoController::class, 'storeResults']);

Route::post('/cierre-mesa/partido/store-results', [CierreCandidatoController::class, 'storePartidoResults']);
Route::get('/cierre-mesa/partido/get-resultadods/{mesa_id}', [CierreCandidatoController::class, 'getResultadosPartido']);

Route::post('/reporte-dashboard/generate', [DashboardController::class, 'generate']);

Route::post("admin/orden-operaciones", [OrderOperacionesController::class, 'store']);
Route::get("admin/orden-operaciones", [OrderOperacionesController::class, 'fetch']);
Route::delete("admin/orden-operaciones/{id}", [OrderOperacionesController::class, 'delete']);