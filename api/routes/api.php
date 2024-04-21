<?php

use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\API\PanierController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommandeProduitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('produits', ProduitController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('paniers', PanierController::class);
Route::apiResource('details_commande', CommandeProduitController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('commandes', CommandeController::class);

Route::post('code', [UserController::class, 'store_verification']);
Route::post('login_code', [UserController::class, 'login_code']);
Route::post('login', [UserController::class, 'login']);

Route::get('search_produit', [ProduitController::class, 'search_produit']);
Route::get('filtre/{id}', [ProduitController::class, 'filtre']);
Route::get('filtre_category', [ProduitController::class, 'filtre_category']);
Route::get('index/{user}', [PanierController::class, 'index']);
Route::get('commande/{user}', [CommandeController::class, 'index']);
Route::get('details/{commande_id}', [CommandeProduitController::class, 'index']);







