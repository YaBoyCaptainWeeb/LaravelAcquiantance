<?php

use App\Http\Controllers\IncomesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StocksController;
use App\Models\ImportTracker;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::post('/RefreshStocks', [StocksController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshIncomes', [IncomesController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshSales', [SalesController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::post('/RefreshOrders', [OrdersController::class, '__invoke'])
    ->middleware('throttle:10,1');

Route::get('/refresh-status/{trackerId}', function ($trackerId) {
   $total = Redis::client()->get("import:$trackerId:total");
   $processed = Redis::client()->get("import:$trackerId:processed");

   if ($total !== null)
   {
       $total = (int) $total;
       $processed = (int) $processed;
       $completed = $processed >= $total;
   } else
   {
       $tracker = ImportTracker::query()->where(['tracker_id' => $trackerId])->first();
        if (!$tracker)
        {
            return response()->json(['error' => 'Tracker not found'], 404);
        }
        $total = $tracker->total_pages;
        $processed = $tracker->processed_pages;
        $completed = $tracker->status;
   }


   return response()->json([
       'tracker_id' => $trackerId,
       'total' => $total,
       'processed' => $processed,
       'status' => !$completed ? 'processing' : $completed
   ]);
});
