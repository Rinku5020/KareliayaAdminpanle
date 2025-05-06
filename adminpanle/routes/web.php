<?php

use App\Http\Controllers\DigitalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DigitalController::class, 'index'])->name('dashboard');

Route::get('/store', [DigitalController::class, 'showStore'])->name('store');