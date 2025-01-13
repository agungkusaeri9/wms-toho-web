<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CheckController;
use App\Http\Controllers\API\V1\InformationController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\QrGeneratorController;
use App\Http\Controllers\API\V1\ResponseFormatter;
use App\Http\Controllers\API\V1\StockInController;
use App\Http\Controllers\API\V1\StockOutController;
use App\Http\Controllers\API\V2\EmployeeController;
use App\Http\Controllers\API\V2\GenerateController;
use App\Http\Controllers\API\V2\StockInController as V2StockInController;
use App\Http\Controllers\API\V2\StockOutController as V2StockOutController;
use App\Models\Generate;
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

Route::get('check', [CheckController::class, 'check']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::middleware(['auth.jwt', 'api'])->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::get('products', [ProductController::class, 'all']);
    Route::post('check-qr', [QrGeneratorController::class, 'checkQr']);
    Route::post('stock-in/create', [StockInController::class, 'store']);
    Route::post('stock-out/create', [StockOutController::class, 'store']);
    Route::get('info-today', [InformationController::class, 'getInfoToday']);
});


Route::prefix('v2')->group(function () {
    Route::get('employees', [EmployeeController::class, 'all']);
    Route::post('stock-in', [V2StockInController::class, 'store']);
    Route::post('stock-out', [V2StockOutController::class, 'store']);
    Route::post('check-qr', [GenerateController::class, 'checkQr']);


    // example
    Route::get('/generates', function () {
        $items = Generate::with('product')->latest()->get();
        return ResponseFormatter::success($items, 'Fetch All Generates', 200);
    });
});
