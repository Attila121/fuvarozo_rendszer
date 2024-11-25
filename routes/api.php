<?php

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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;



// Public routes (no authentication required)
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/jobs', [JobController::class, 'index']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{job}', [JobController::class, 'update']);
    Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
    Route::post('/jobs/{job}/assign', [JobController::class, 'assignDriver']);

    Route::get('/drivers', [UserController::class, 'drivers']);
});
