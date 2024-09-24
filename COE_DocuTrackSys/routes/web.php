<?php

// VISION CUBE SOFTWARE CO. 
// Routes
// Facilitates the routing of the system and its corresponding pages in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\VerifyAccount;
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

// Show Log In Admin Form
Route::get('/account/login/admin', function () {
    return view('loginAdmin');
})->name('account.showLogInAdmin');

// Log In Admin
Route::post('/account/login/admin', [AccountController::class, 'loginAdmin'])->name('account.loginAdmin');

// Log Out
Route::post('/account/logout', [AccountController::class, 'logout'])->name('account.logout');

// Show Forgot Password Form
Route::get('/account/forgot-password', function(){
    return view('forgotPassword');
})->name('account.showForgotPassword');

// Forgot Password
Route::post('/account/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');

// Dashboard
Route::get('/dashboard', [AccountController::class, 'showDashboard'])->name('account.dashboard')->middleware([
    VerifyAccount::class,
    NoCache::class
]);

// Store Documents
Route::post('/dashboard/add', [DocumentController::class, 'store'])->name('document.store');
    
// Get Incoming Documents
Route::get('/documents/incoming', [DocumentController::class, 'showIncoming'])->name('documents.showIncoming');

// Get Outgoing Documents
Route::get('/documents/outgoing', [DocumentController::class, 'showOutgoing'])->name('documents.showOutgoing');

// Download Documents
Route::get('/download/{id}', [DocumentController::class, 'download'])->name('document.download');
require __DIR__.'/auth.php';