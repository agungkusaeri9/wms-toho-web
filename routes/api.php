<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\QrGeneratorController;
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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::middleware(['auth.jwt'])->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('check-qr', [QrGeneratorController::class, 'checkQr']);
});
