<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;

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

Route::post('/register',[AuthController::class,'Register']);
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1');
Route::post('/verify-code',[AuthController::class,'VerifyCode']);
Route::post('/resend-code',[AuthController::class,'ResendCode'])->middleware('throttle:3,10');
Route::post('/forget-password',[ForgetPasswordController::class,'forgotPassword']);
Route::post('/reset-password',[ForgetPasswordController::class,'resetPassword']);



Route::middleware('auth:sanctum')->group( function () {
    
    
    Route::post('/refresh-token',[AuthController::class,'refreshToken']);
    Route::post('/logout',[AuthController::class,'logout']);
});
