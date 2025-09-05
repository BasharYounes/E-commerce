<?php

use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;


Route::prefix('evaluation')->group(function () {

    Route::middleware(['auth:sanctum', 'banned'])->group(function () {
        Route::post('/create',    [EvaluationController::class, 'store']);
        
    });
});
Route::prefix('admin')->group(function () {
    Route::post('/getAll',    [EvaluationController::class, 'index']);
});