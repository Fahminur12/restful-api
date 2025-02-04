<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/categories', CategoriesController::class);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
