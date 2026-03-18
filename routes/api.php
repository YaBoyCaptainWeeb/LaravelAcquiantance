<?php

use App\Http\Controllers\IncomesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StocksController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/RefreshStocks', [StocksController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshIncomes', [IncomesController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshSales', [SalesController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshOrders', [OrdersController::class, '__invoke'])
    ->middleware('throttle:10,1');
