<?php
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',           [AuthApiController::class, 'me']);
    Route::get('/absensi',      [AuthApiController::class, 'listAbsensi']);
    Route::post('/absensi',     [AuthApiController::class, 'storeAbsensi']);
    Route::post('/logout',      [AuthApiController::class, 'logout']);
});