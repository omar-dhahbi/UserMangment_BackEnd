<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\departementsController;
use App\Http\Controllers\reunionController;
use App\Http\Controllers\stagiaireController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ClientController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
//stagiaires
Route::get('stagiaires', [stagiaireController::class, 'index']);
Route::get('stagiaires/{id}', [stagiaireController::class, 'getstagiairesById']);
Route::post('stagiaires', [stagiaireController::class, 'store']);
Route::put('stagiaires/{id}', [stagiaireController::class, 'update']);
Route::delete('stagiaires/{id}', [stagiaireController::class, 'destroy']);
Route::get('searchStagiaire/search', [stagiaireController::class, 'search']);
//departements
Route::get('departements', [departementsController::class, 'index']);
Route::get('departements/{id}', [departementsController::class, 'getdepartementsById']);
Route::post('departements', [departementsController::class, 'store']);
Route::put('departements/{id}', [departementsController::class, 'update']);
Route::delete('departements/{id}', [departementsController::class, 'destroy']);
Route::get('searchDepartement/search', [departementsController::class, 'search']);
//client
Route::get('clients', [ClientController::class, 'index']);
Route::get('clients/{id}', [ClientController::class, 'getclientsById']);
Route::post('clients', [ClientController::class, 'store']);
Route::put('clients/{id}', [ClientController::class, 'update']);
Route::delete('clients/{id}', [ClientController::class, 'destroy']);
//reunion
Route::get('reunion', [reunionController::class, 'index']);
Route::get('reunion/{id}', [reunionController::class, 'getsReunionById']);
Route::post('reunion', [reunionController::class, 'store']);
Route::put('reunion/{id}', [reunionController::class, 'update']);
Route::delete('reunion/{id}', [reunionController::class, 'destroy']);
Route::get('reunion', [reunionController::class,'trashed']);
Route::post('reunion/{id}', [reunionController::class, 'restore']);
//projets
Route::get('projets', [ProjetController::class, 'index']);
Route::get('projets/{id}', [ProjetController::class, 'getProjetById']);
Route::post('projets', [ProjetController::class, 'store']);
Route::put('projets/{id}', [ProjetController::class, 'update']);
Route::delete('projets/{id}', [ProjetController::class, 'destroy']);
Route::get('projets', [ProjetController::class,'trashed']);
Route::post('projets/{id}', [ProjetController::class, 'restore']);
//Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

