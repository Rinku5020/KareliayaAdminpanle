<?php

use App\Http\Controllers\DigitalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('digital.dashboard'); // Ensure 'digital.dashboard' view exists in resources/views/digital/dashboard.blade.php
});


Route::get('/store', [DigitalController::class, 'showStore'])->name('store');