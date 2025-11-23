<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


Route::get('/members', [MemberController::class, 'index']);
Route::post('/members', [MemberController::class, 'store']);
Route::put('/members/{id}', [MemberController::class, 'update']);
Route::delete('/members/{id}', [MemberController::class, 'destroy']);

Route::get('/inventory', [Controller::class, 'index']);
Route::post('/inventory', [Controller::class, 'store']);
Route::put('/inventory/{id}', [Controller::class, 'update']);
Route::delete('/inventory/{id}', [Controller::class, 'destroy']);
