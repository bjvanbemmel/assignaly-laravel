<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Route::controller(AuthController::class)->group(function() {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(InstituteController::class)->group(function() {
        Route::get('/institutes', 'index');
        Route::get('/institutes/{institute}', 'show');
        Route::post('/institutes', 'store');
        Route::put('/institutes/{institute}', 'update');
        Route::delete('/institutes/{institute}', 'destroy');
    });

    Route::controller(UserController::class)->group(function() {
        Route::get('/users', 'index');
        Route::get('/users/{user}', 'show');
        Route::post('/users', 'store');
        Route::put('/users/{user}', 'update');
        Route::delete('/users/{user}', 'destroy');
    });

    Route::controller(RoleController::class)->group(function() {
        Route::get('/roles', 'index');
        Route::get('/roles/{role}', 'show');
        Route::post('/roles', 'store');
        Route::put('/roles/{role}', 'update');
        Route::delete('/roles/{role}', 'destroy');
    });

    Route::controller(AssignmentController::class)->group(function() {
        Route::get('/assignments', 'index');
        Route::get('/assignments/latest', 'latest');
        Route::get('/assignments/{assignment}', 'show');
        Route::post('/assignments', 'store');
        Route::put('/assignments/{assignment}', 'update');
        Route::delete('/assignments/{assignment}', 'destroy');
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/validate', [AuthController::class, 'get']);
});

