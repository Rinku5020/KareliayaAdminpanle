<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('playlists/{id}', [ApiController::class, 'getAllData'])->name('getAllData');
Route::post('devices', [ApiController::class, 'devices'])->name('devices');
Route::post('verify-codes', [ApiController::class, 'verifyCode'])->name('verifyCode');
