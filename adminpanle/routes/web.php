<?php

use App\Http\Controllers\DigitalController;
use App\Http\Controllers\GraphicsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DigitalController::class, 'index'])->name('dashboard');

Route::get('/store', [DigitalController::class, 'showStore'])->name('store');
Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
Route::get('/layout', [DigitalController::class, 'showLayout'])->name('layout');

Route::get('/display',[DigitalController::class,'showDisplay'])->name('display');
Route::get('/graphics',[GraphicsController::class,'showGraphicsAndVideos'])->name('graphics');
Route::get('/addGraphics',[GraphicsController::class,'addGraphicsAndVideos'])->name('addGraphics');
Route::post('/createGraphics',[GraphicsController::class,'createGraphics'])->name('CreateGraphics');

