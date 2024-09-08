<?php

// VISION CUBE SOFTWARE CO. 
// Routes
// Facilitates the routing of the system and its corresponding pages in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Log In page
Route::get('/', function(){
    return view('createAccount');
})->name('account.create');

// Create Account
Route::post('/account/store', [AccountController::class, 'store'])->name('account.store');

// Show Log In Form
Route::get('/account/login', function () {
    return view('login');
})->name('account.showLogIn');

// Log In Account
Route::post('/account/login', [AccountController::class, 'login'])->name('account.login');

// Show Forgot Password Form
Route::get('/account/forgot-password', function(){
    return view('forgotPassword');
})->name('account.showForgotPassword');

// Forgot Password
Route::post('/account/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('account.dashboard');

require __DIR__.'/auth.php';
