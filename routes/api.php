<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;

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

Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('user', [UserController::class, 'index']);
Route::post('user', [UserController::class, 'create']);
Route::get('/positions', [PositionController::class, 'index']);
Route::get('/tokens', [UserController::class, 'token']);
