<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return Inertia::render('home');
});

Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');

Route::get('/login', [AuthController::class,'loginPage']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::resource('/settings', SSLCommerzCredentialController::class);