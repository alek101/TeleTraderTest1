<?php

use App\Http\Controllers\BitfinexController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;

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

Route::get('/login',[CookieController::class, 'login']);

Route::get('/fav/{fav}',[CookieController::class, 'handleFavorites']);

Route::get('/status',[CookieController::class, 'status']);

Route::get('/getSymbols',[BitfinexController::class, 'getSymbols']);