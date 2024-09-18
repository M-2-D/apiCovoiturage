<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConducteurController;
use App\Http\Controllers\PassagerController;
use App\Http\Controllers\AdministrateurController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LieuxController;


// Routes d'authentification
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('registerConducteur', [AuthController::class, 'registerConducteur']);
Route::post('registerAdmin', [AuthController::class, 'registerAdmin']);
Route::post('registerPassager', [AuthController::class, 'registerPassager']);
Route::middleware('auth:sanctum')->get('/users/showProfile', [UserController::class, 'showProfile']);
Route::middleware('auth:sanctum')->put('/users/updateProfile', [UserController::class, 'updateProfile']);

Route::get('/lieux', [LieuxController::class, 'index']);
Route::get('/lieux/{id}', [LieuxController::class, 'show']);
Route::post('/lieux', [LieuxController::class, 'store']);
Route::put('/lieux/{id}', [LieuxController::class, 'update']);
Route::delete('/lieux/{id}', [LieuxController::class, 'destroy']);


Route::middleware('auth:sanctum')->group(function () {
    // Routes pour les Conducteurs
    Route::middleware('role:Conducteur')->group(function () {
        Route::apiResource('trajets', TrajetController::class);
        Route::apiResource('vehicules', VehiculeController::class);
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm']);
        Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);

    });

    // Routes pour les Passagers
    Route::middleware('role:Passager')->group(function () {
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
        
    });

    // Routes pour les Administrateurs
    Route::middleware('role:Administrateur')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('conducteurs', ConducteurController::class);
        Route::apiResource('passagers', PassagerController::class);
        Route::apiResource('administrateurs', AdministrateurController::class);
        Route::apiResource('paiements', PaiementController::class);
        Route::apiResource('paiement-liquides', PaiementLiquideController::class);
        Route::apiResource('paiement-moyen-de-transferts', PaiementMoyenDeTransfertController::class);
        Route::post('/users/{id}/toggle-block', [UserController::class, 'toggleBlock']);
    });

    // Routes accessibles à tous les utilisateurs authentifiés
    Route::post('/trajets/recherche', [TrajetController::class, 'rechercherTrajets'])->name('trajets.recherche');
    Route::middleware('auth:sanctum')->get('/conducteur/{id}/reservations', [ReservationController::class, 'getReservationsForConducteur']);
    // api.php

    Route::middleware('auth:sanctum')->get('/conducteur/{id}/passagers', [ReservationController::class, 'getPassengersForConducteur']);
    Route::get('reservations/conducteur/{conducteurId}', [ReservationController::class, 'getReservationsByConducteur']);
    Route::get('/trajets/{id}/reservations', [TrajetController::class, 'getReservations']);
    Route::middleware('auth:sanctum')->get('/conducteur/reservations', [ReservationController::class, 'indexForConducteur']);


    
});
