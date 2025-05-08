<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DigitalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DigitalController::class, 'index'])->name('dashboard');
Route::get('/login',[Controller::class, 'showLogin'])->name('showLogin');
Route::get('/login-success',[Controller::class, 'login'])->name('login');

Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
Route::get('/layout', [DigitalController::class, 'showLayout'])->name('layout');

Route::get('/display',[DigitalController::class,'showDisplay'])->name('display');
Route::get('/graphics',[DigitalController::class,'showGraphicsAndVideos'])->name('graphics');

// Store Routes
Route::get('/store', [DigitalController::class, 'showStore'])->name('store');
Route::get('/addNewStore', [DigitalController::class, 'addStore'])->name('newStore');
Route::get('/store-add',[DigitalController::class,'createStore'])->name('storeAdd');
