<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\GetMeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/',
    function (){
        return response()->json(['data' => ['Hello World']]);
    }
);

Route::post('auth/create/{provider}', [CreateUserController::class, 'store'])->name('createUser');
Route::post('auth/login/{provider}', [LoginController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')
    ->group(function (){
        Route::post('/auth/me', [GetMeController::class, 'me'])
            ->name('me');

        Route::post('/auth/transactions', [TransactionController::class, 'postTransaction'])
            ->name('postTransaction');

        Route::post('/auth/{provider}', [AuthController::class, 'postAuthenticate'])
            ->name('authenticate');

    }
);
