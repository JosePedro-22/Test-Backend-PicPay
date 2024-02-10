<?php

use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
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
Route::post('/transaction', [TransferController::class, 'transfer'])->name('transaction');

Route::middleware('auth:sanctum')->group(function (){
    //Route test
    Route::get('/test',function (){return response()->json(['data' => 'Hello World']);})->name('test');


});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
