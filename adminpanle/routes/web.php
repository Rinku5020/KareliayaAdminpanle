<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DigitalController;
use App\Http\Controllers\GraphicsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DigitalController::class, 'index'])->name('dashboard');
Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
Route::get('/layout', [DigitalController::class, 'showLayout'])->name('layout');
Route::get('/display',[DigitalController::class,'showDisplay'])->name('display');
Route::get('/graphics',[DigitalController::class,'showGraphicsAndVideos'])->name('graphics');

// Store Routes
Route::get('/store', [DigitalController::class, 'showStore'])->name('store');
Route::get('/add-new-store', [DigitalController::class, 'addNewStore'])->name('newStore');
Route::post('/store-add',[DigitalController::class,'createStore'])->name('storeAdd');
Route::get('/edit-store/{storeId}', [DigitalController::class, 'editStore'])->name('editStore');
Route::put('/update-store/{id}', [DigitalController::class, 'updateStore'])->name('updateStore');
Route::delete('/delete-store/{id}', [DigitalController::class, 'deleteStore'])->name('deleteStore');
Route::post('/store/status/{id}',[DigitalController::class, 'status'])->name('status');

// Graphics and Videos Controller
Route::get('/graphics',[GraphicsController::class,'showGraphicsAndVideos'])->name('graphics');
Route::get('/addGraphics',[GraphicsController::class,'addGraphicsAndVideos'])->name('addGraphics');
Route::post('/createGraphics',[GraphicsController::class,'createGraphics'])->name('CreateGraphics');

// Login and Register Controller
Route::get('/login',[Controller::class, 'showLogin'])->name('showLogin');
Route::post('/login-success',[Controller::class, 'loginValidateUser'])->name('loginValidateUser');
Route::get('/register',[Controller::class, 'userRegister'])->name('register');
