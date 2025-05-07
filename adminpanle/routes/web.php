<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DigitalController;
use App\Http\Controllers\GraphicsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DigitalController::class, 'index'])->name('dashboard');
Route::get('/login',[Controller::class, 'showLogin'])->name('showLogin');
Route::get('/login-success',[Controller::class, 'login'])->name('login');

Route::get('/store', [DigitalController::class, 'showStore'])->name('store');
Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
Route::get('/layout', [DigitalController::class, 'showLayout'])->name('layout');

Route::get('/display',[DigitalController::class,'showDisplay'])->name('display');
<<<<<<< HEAD
Route::get('/graphics',[GraphicsController::class,'showGraphicsAndVideos'])->name('graphics');
Route::get('/addGraphics',[GraphicsController::class,'addGraphicsAndVideos'])->name('addGraphics');
Route::post('/createGraphics',[GraphicsController::class,'createGraphics'])->name('CreateGraphics');

=======
Route::get('/graphics',[DigitalController::class,'showGraphicsAndVideos'])->name('graphics');
Route::get('/addNewStore', [DigitalController::class, 'addNewStore'])->name('newStore');
>>>>>>> 3ffe67db28d35aa201cb087a1564a8bef12cc8ee
