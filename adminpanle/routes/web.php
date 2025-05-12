<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DigitalController;
use App\Http\Controllers\GraphicsController;
use App\Http\Controllers\LayoutController;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', [DigitalController::class, 'index'])->name('dashboard');
Route::get('/store', [DigitalController::class, 'showStore'])->name('store');
Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
Route::get('/display',[DigitalController::class,'showDisplay'])->name('display');
Route::get('/addNewStore', [DigitalController::class, 'addNewStore'])->name('newStore');


// Graphics and Videos Controller
Route::get('/graphics',[GraphicsController::class,'showGraphicsAndVideos'])->name('graphics');
Route::get('/addGraphics',[GraphicsController::class,'addGraphicsAndVideos'])->name('addGraphics');
Route::post('/createGraphics',[GraphicsController::class,'createGraphics'])->name('CreateGraphics');

// Login and Register Controller
Route::get('/login',[Controller::class, 'showLogin'])->name('showLogin');
Route::post('/login-success',[Controller::class, 'loginValidateUser'])->name('loginValidateUser');
Route::get('/register',[Controller::class, 'userRegister'])->name('register');
Route::post('/register',[Controller::class, 'registerValidateUser'])->name('registerValidateUser');
Route::get('/email',[Controller::class, 'EmailVerify'])->name('emailVerify');
Route::post('/email',[Controller::class, 'Verification'])->name('Verification');
Route::get('/otp',[Controller::class, 'otp'])->name('otp');
Route::post('/sendOtp', [Controller::class, 'sendVerification'])->name('sendOtp');
Route::get('/resetpassword', [Controller::class, 'resetpassword'])->name('resetpassword');
Route::post('/resetpassword', [Controller::class, 'resetPasswordUpdate'])->name('password.update');

// Layout 
Route::get('/layout', [LayoutController::class, 'showLayout'])->name('layout');
Route::get('/addlayout', [LayoutController::class, 'AddLayout'])->name('addlayout');
Route::post('/layoutStore', [LayoutController::class, 'layoutStore'])->name('layoutStore');





