<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::prefix('report')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create',    [ReportController::class, 'store']);
        Route::post('/getAll',    [ReportController::class, 'index']);
    });
});
