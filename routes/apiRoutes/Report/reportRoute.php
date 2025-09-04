<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::prefix('report')->group(function () {

    Route::middleware(['auth:sanctum', 'banned'])->group(function () {
        Route::post('/create',    [ReportController::class, 'store']);
        Route::post('/getAll',    [ReportController::class, 'index']);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('active-adv/{id}',[ReportController::class,'activeAdv']);
    Route::post('un-active-adv/{id}',[ReportController::class,'unActiveAdv']);
});
