<?php
 use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Api\AuthController;
 use App\Http\Controllers\Api\UserController;
 use App\Http\Controllers\Api\BanqueController;
 use App\Http\Controllers\Api\CompteController;
 use App\Http\Controllers\Api\TransactionController;
 use App\Http\Controllers\Api\StatController;
 use App\Http\Controllers\Api\MessageController;


 Route::post('register', [AuthController::class, 'register']);
 Route::post('login', [AuthController::class, 'login']);

 Route::middleware('auth:api')->group(function () {
     Route::apiResource('users', UserController::class);
     Route::apiResource('banques', BanqueController::class);
     Route::apiResource('comptes', CompteController::class);
    Route::apiResource('transactions', TransactionController::class);
     Route::apiResource('stats', StatController::class);
     Route::get('user-stats', [StatController::class, 'userStats']);
      Route::apiResource('messages', MessageController::class);

     
     Route::post('logout', [AuthController::class, 'logout']);
});