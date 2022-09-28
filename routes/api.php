<?php

use App\Http\Controllers\InstituteController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(InstituteController::class)->group(function() {
    Route::get('/institutes', 'index');
    Route::get('/institutes/{institute}', 'show');
    Route::post('/institutes', 'store');
    Route::put('/institutes/{institute}', 'update');
    Route::delete('/institutes/{institute}', 'destroy');
});
