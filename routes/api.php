<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\departementsController;
use App\Http\Controllers\reunionController;
use App\Http\Controllers\stagiaireController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployééController;

use App\Http\Controllers\ReunionDeptartement;



use App\Http\Middleware\RoleMiddleware;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




//stagiaires
Route::group(['prefix' => 'stagiaires'], function () {
    Route::get('get_stagiaires', [stagiaireController::class, 'index'])->middleware(['auth:api', 'role:admin']);
    Route::get('get_Stagiaire/{id}', [stagiaireController::class, 'getstagiairesById'])->middleware(['auth:api', 'role:admin']);
    Route::post('Add_Stagiaire', [stagiaireController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::put('update_Stagiaire/{id}', [stagiaireController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_Stagiare/{id}', [stagiaireController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
    Route::get('searchStagiaire/search', [stagiaireController::class, 'search'])->middleware(['auth:api', 'role:admin']);
});
//departements
Route::group(['prefix' => 'departements'], function () {
    Route::get('get_departements', [departementsController::class, 'index'])->middleware(['auth:api', 'role:admin', 'cors']);
    Route::get('get_departements/{id}', [departementsController::class, 'getdepartementsById'])->middleware(['auth:api', 'role:admin']);
    Route::post('add_departement', [departementsController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::put('update_departement/{id}', [departementsController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_departement/{id}', [departementsController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
    Route::get('searchDepartement/search', [departementsController::class, 'search'])->middleware(['auth:api', 'role:admin']);
});
//client
Route::group(['prefix' => 'clients'], function () {
    Route::get('searchClient/search', [ClientController::class, 'search'])->middleware(['auth:api', 'role:admin']);
    Route::get('get_Clients', [ClientController::class, 'index'])->middleware(['auth:api', 'role:admin']);
    Route::get('get_Client/{id}', [ClientController::class, 'getclientsById'])->middleware(['auth:api', 'role:admin']);
    Route::post('add_Client', [ClientController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::Put('update_Client/{id}', [ClientController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_Client/{id}', [ClientController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
});
//reunionds
Route::group(['prefix' => 'reunions'], function () {
    Route::get('get_reunion/{id}', [reunionController::class, 'getDataByInd'])->middleware(['auth:api', 'role:admin']);
    Route::get('reunion/{id}', [reunionController::class, 'getDataById'])->middleware(['auth:api', 'role:admin']);

    Route::post('add_reunion', [reunionController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::put('update_reunion/{id}', [reunionController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_reunions/{id}', [reunionController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
    Route::get('searchReunion/search', [ProjetController::class, 'search'])->middleware(['auth:api', 'role:admin']);
});
//congé
Route::group(['prefix' => 'conge'], function () {
    Route::post('addConge', [EmployééController::class, 'demandeConge'])->middleware(['auth:api', 'role:employee']);
    Route::get('getResultByUserId/{user_id}', [EmployééController::class, 'getResultByUserId'])->middleware(['auth:api', 'role:employee']);
});
Route::group(['prefix' => 'employee'], function () {
    Route::get('getReunionByUser/{id}', [EmployééController::class, 'getReunionByUser'])->middleware(['auth:api', 'role:employee']);
    Route::get('searchConge/search', [EmployééController::class, 'searchConge'])->middleware(['auth:api', 'role:employee']);
    Route::put('updatepassword/{id}', [EmployééController::class, 'updatepassword'])->middleware(['auth:api', 'role:employee']);
    Route::put('updateUser/{id}', [EmployééController::class, 'UpdateUser'])->middleware(['auth:api', 'role:employee']);
    Route::get('getContratByUserId/{user_id}', [EmployééController::class, 'getContratByUserId'])->middleware(['auth:api', 'role:employee,admin']);
});

//projets
Route::group(['prefix' => 'projets'], function () {
    Route::get('get_projets', [ProjetController::class, 'index'])->middleware(['auth:api', 'role:admin']);
    Route::get('get_projet/{id}', [ProjetController::class, 'getProjetById'])->middleware(['auth:api', 'role:admin']);
    Route::post('add_projet', [ProjetController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::put('update_projet/{id}', [ProjetController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_projets/{id}', [ProjetController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
    Route::get('searchProjet/search', [ProjetController::class, 'search'])->middleware(['auth:api', 'role:admin']);
});
//contrats
Route::group(['prefix' => 'contrats'], function () {
    Route::get('get_contrats', [ContratController::class, 'index'])->middleware(['auth:api', 'role:admin']);
    Route::get('get_contrat/{id}', [ContratController::class, 'getcontratsById'])->middleware(['auth:api', 'role:admin']);
    Route::post('add_contrat', [ContratController::class, 'store'])->middleware(['auth:api', 'role:admin']);
    Route::put('update_contrtat/{id}', [ContratController::class, 'update'])->middleware(['auth:api', 'role:admin']);
    Route::delete('delete_contrats/{id}', [ContratController::class, 'destroy'])->middleware(['auth:api', 'role:admin']);
    Route::get('searchContrat/search', [ProjetController::class, 'search'])->middleware(['auth:api', 'role:admin']);
});
//Auth
Route::group(['prefix' => 'auth'],  function () {
    Route::put('restarpasword/{email}', [AuthController::class, 'restarpassword']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('index', [AuthController::class, 'index']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('updatepassword/{id}', [AuthController::class, 'updatepassword']);
    Route::get('User/{id}', [AuthController::class, 'getUserById']);
    Route::put('verifMail/{id}', [AuthController::class, 'verifMail']);
});
//Admin
Route::group(
    ['prefix' => 'admin'],
    function () {
        Route::put('updateUser/{id}', [AdminController::class, 'UpdateUser'])->middleware(['auth:api', 'role:admin']);
        Route::get('users', [AdminController::class, 'userList'])->middleware(['auth:api', 'role:admin']);
        Route::put('activeAccount/{id}', [AdminController::class, 'activeAccount'])->middleware(['auth:api', 'role:admin']);
        Route::put('AccounNotActive/{id}', [AdminController::class, 'AccounNotActive'])->middleware(['auth:api', 'role:admin']);
        Route::get('searchUsers/search', [AdminController::class, 'search'])->middleware(['auth:api', 'role:admin']);
        Route::get('countUser', [AdminController::class, 'user'])->middleware(['auth:api', 'role:admin']);
        Route::get('countDepartement', [AdminController::class, 'departements'])->middleware(['auth:api', 'role:admin']);
        Route::get('countProjet', [AdminController::class, 'projets'])->middleware(['auth:api', 'role:admin']);
        Route::get('countClient', [AdminController::class, 'clients'])->middleware(['auth:api', 'role:admin']);
        Route::get('getCongéById/{id}', [AdminController::class, 'getCongéById'])->middleware(['auth:api', 'role:admin']);
        Route::post('UserMail/{email}', [AdminController::class, 'Users'])->middleware(['auth:api', 'role:admin']);
        Route::put('updatepassword/{id}', [AdminController::class, 'updatepassword'])->middleware(['auth:api', 'role:admin']);
        Route::put('accepter/{id}', [AdminController::class, 'accepter'])->middleware(['auth:api', 'role:admin']);
        Route::put('refuser/{id}', [AdminController::class, 'refuser'])->middleware(['auth:api', 'role:admin']);
        Route::get('getCongeNotAccepted', [AdminController::class, 'getCongéNotAccepted'])->middleware(['auth:api', 'role:admin']);
        Route::get('getCongeAccepted', [AdminController::class, 'getCongéAccepted'])->middleware(['auth:api', 'role:admin']);
        Route::get('getCongeAttente', [AdminController::class, 'getCongéAttente'])->middleware(['auth:api', 'role:admin']);
        Route::get('getUserById/{id}', [AdminController::class, 'getUserById'])->middleware(['auth:api', 'role:admin,employee']);
        Route::get('getIdUser/{id}', [AdminController::class, 'getIdUser'])->middleware(['auth:api', 'role:admin']);
        Route::get('searchConge/search', [AdminController::class, 'searchConge'])->middleware(['auth:api', 'role:admin']);
        Route::get('getReunionByDepartement', [ReunionDeptartement::class, 'getReunionByDepartement'])->middleware(['auth:api', 'role:admin']);
    }
);
