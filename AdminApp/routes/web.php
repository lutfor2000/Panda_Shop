<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;

Route::redirect("/", '/Dashboard');

Route::get('/login', [AuthController::class,'loginPage']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');

Route::resource('/settings', SSLCommerzCredentialController::class);
Route::resource('/brands', BrandController::class);


