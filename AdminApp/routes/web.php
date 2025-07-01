<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;

Route::get('/', function () {
    return Inertia::render('home');
});

Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');

Route::resource('/settings', SSLCommerzCredentialController::class);