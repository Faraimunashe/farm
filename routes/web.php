<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Farmer\FarmController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthenticationController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');


    Route::resource('profile', ProfileController::class);
});

Route::prefix('farmer')->middleware(['auth', 'role:farmer'])->group(function () {
    Route::resource('farm', FarmController::class);
});
