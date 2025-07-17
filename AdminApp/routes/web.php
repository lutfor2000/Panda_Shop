<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ProductController;

Route::redirect("/", '/Dashboard');

Route::get('/login', [AuthController::class,'loginPage']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');
Route::resource('/settings', SSLCommerzCredentialController::class);

Route::resource('/sliders', SliderController::class);

Route::resource('/brands', BrandController::class);

Route::resource('/categories', CategoryController::class);
Route::resource('/products', ProductController::class);


