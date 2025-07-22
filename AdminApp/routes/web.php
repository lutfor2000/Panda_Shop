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
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AllProductController;
use App\Models\ProductDetail;

// Route::redirect("/", '/Dashboard');

Route::get('/login', [AuthController::class,'loginPage']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//=======================Backend Part================================================

Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');
Route::resource('/settings', SSLCommerzCredentialController::class);

Route::resource('/sliders', SliderController::class);

Route::resource('/brands', BrandController::class);

Route::resource('/categories', CategoryController::class);
Route::resource('/products', ProductController::class);



//=================Frontend Part============================================================

Route::resource("/", HomeController::class);
Route::resource("/allproducts", AllProductController::class);
Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
