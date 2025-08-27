<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function WishlistPage(){
        return Inertia::render('Frontend/Wishlists/Wishlists');
    }
}
