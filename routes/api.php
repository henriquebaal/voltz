<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\InventoryController;

// Rotas de usuários
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Rotas de pedidos
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::get('/orders/history', [OrderController::class, 'getOrderHistory']);
    
    // Rotas de avaliações
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Rotas de promoções
    Route::get('/promotions', [PromotionController::class, 'index']);
    Route::post('/promotions', [PromotionController::class, 'store']);

    // Rotas de estoque
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory', [InventoryController::class, 'store']);
});

