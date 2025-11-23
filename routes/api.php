<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\InventoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('members', MemberController::class);
Route::apiResource('categories', CategoryController::class);
Route::get('/categories', [CategoryController::class, 'index']);
Route::apiResource('inventory', InventoryController::class);