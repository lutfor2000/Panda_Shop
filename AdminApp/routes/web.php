<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SSLCommerzCredentialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AllProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\UserAuthController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CheckoutController;


// Route::redirect("/", '/Dashboard');

//=======================Backend Part================================================

//-------------Backend AuthController Start--------------
Route::get('/login', [AuthController::class,'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//-------------Backend AuthController End--------------

Route::middleware(['admin','auth'])->group(function(){
    
    Route::get('/login/out', [AuthController::class, 'loginOut'])->name('login.out');

    Route::get('/Dashboard',[DashboardController::class,'index'])->name('page.dashboard');
    Route::resource('/settings', SSLCommerzCredentialController::class);

    Route::resource('/sliders', SliderController::class);

    Route::resource('/brands', BrandController::class);

    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);

});

//=================Frontend Part============================================================

Route::resource("/", HomeController::class);
Route::resource("/allproducts", AllProductController::class);



//---------------------UserAuthController Start---------------------------------------------
Route::get('/user/login', [UserAuthController::class, 'loginPage'])->name('login');
Route::post('/user/login/post', [UserAuthController::class, 'loginPost'])->name('login.post');

Route::get('/user/register', [UserAuthController::class, 'registerPage'])->name('register');
Route::post('/user/register', [UserAuthController::class, 'registerPost'])->name('register.post');

Route::get('/UserVerify', [UserAuthController::class, 'UserVerifyPage'])->name('UserVerify');

Route::post('/otp/UserVerify', [UserAuthController::class, 'UserOTPVerify'])->name('verify.otp.post');
Route::post('/otp/resend', [UserAuthController::class, 'OtpResend'])->name('resend.otp.post');


//---------------------UserAuthController End---------------------------------------------

Route::middleware(["auth"])->group(function(){

    Route::get('/userLogout',[UserAuthController::class,'UserLogout'])->name('user.logout');
    
    Route::resource("/userdashboards", UserDashboardController::class);
    
    Route::resource("/carts", CartController::class);
    //---------------------DashboardController Start---------------------------------------------

    Route::post('/profile', [UserDashboardController::class, 'profile'])->name('profile');

    Route::post('/profile/mail', [UserDashboardController::class, 'profileMail'])->name('profile.mail.post');

     Route::post('/profile/password', [UserDashboardController::class, 'profilePassword'])->name('profile.password.post');
     //---------------------DashboardController End---------------------------------------------

     //---------------------WishlistController Start---------------------------------------------
     Route::get('/wishlists', [WishlistController::class, 'WishlistPage'])->name('wishlist.page');
     
     
     //---------------------CheckoutController Start---------------------------------------------
     Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

});


