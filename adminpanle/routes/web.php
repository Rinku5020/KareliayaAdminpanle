<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DigitalController;
use App\Http\Controllers\GraphicsController;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', [DigitalController::class, 'index'])->name('dashboard');
Route::get('/template', [DigitalController::class, 'showTemplate'])->name('template');
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
Route::post('/register',[Controller::class, 'registerValidateUser'])->name('registerValidateUser');
Route::get('/email',[Controller::class, 'EmailVerify'])->name('emailVerify');
Route::post('/email',[Controller::class, 'Verification'])->name('Verification');
Route::get('/otp',[Controller::class, 'otp'])->name('otp');
Route::post('/sendOtp', [Controller::class, 'sendVerification'])->name('sendOtp');
Route::get('/resetpassword', [Controller::class, 'resetpassword'])->name('resetpassword');
Route::post('/resetpassword', [Controller::class, 'resetPasswordUpdate'])->name('password.update');

// Layout 
Route::get('/layout', [DigitalController::class, 'showLayout'])->name('layout');
Route::get('/addlayout', [DigitalController::class, 'AddLayout'])->name('addlayout');



// Route::get('/test-mail', function () {
//     Mail::to('rinku4kareliya@gmail.com')->send(new OtpMail('123456'));
//     return 'Mail sent!';
// });

