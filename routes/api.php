<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdvController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\CategoryController;

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

require __DIR__. '/apiRoutes/Report/reportRoute.php';
require __DIR__. '/apiRoutes/Evaluation/evaluationRoute.php';

Route::post('/register',[AuthController::class,'Register']);
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1');
Route::post('/verify-code',[AuthController::class,'VerifyCode']);
Route::post('/resend-code',[AuthController::class,'ResendCode'])->middleware('throttle:3,10');
Route::post('/forget-password',[ForgetPasswordController::class,'forgotPassword']);
Route::post('/check-code',[ForgetPasswordController::class,'checkCode']);



Route::prefix('user')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/update',    [AuthController::class, 'EditInformation']); // edit user info
        Route::get('/favorites', [AdvController::class, 'getUserFavorites']);
        Route::get('/', [AuthController::class, 'getUser']);
    });
});


Route::prefix('adv')->group(function () {
    Route::get('/',     [AdvController::class, 'index']);
    
    


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create',       [AdvController::class, 'store']);
        Route::put('/update/{id}',    [AdvController::class, 'update']);
        Route::delete('/delete/{id}', [AdvController::class, 'destroy']);
        Route::post('/search',[AdvController::class,'search']);

        Route::post('/add-like', [AdvController::class, 'addLike']); // addLike
        Route::post('/remove-like', [AdvController::class, 'removeLike']); // removeLike

        Route::post('/add-favorite', [AdvController::class, 'addToFavorite']); // addFavorite
        Route::post('/remove-favorite', [AdvController::class, 'removeFromFavorite']); // removeFavorite

        Route::get('/all-user-advs',       [AdvController::class, 'userAdvs']);

        Route::get('/show/{id}', [AdvController::class, 'show']);
    });
});

Route::prefix('category')->group(function () {
    Route::get('/',     [CategoryController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create',       [CategoryController::class, 'store']);
        Route::put('/update/{id}',    [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reset-password/{email}',[ForgetPasswordController::class,'resetPassword']);

    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout',        [AuthController::class, 'logout']);
});
