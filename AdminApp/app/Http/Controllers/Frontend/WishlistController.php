<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    public function WishlistPage(){
        $user =Auth::user();
        // $wishlist = $user->profile->wishlists->load('product');
         $wishlist = $user->profile->wishlists->load(['product', 'product.details']);
       
            
        return Inertia::render('Frontend/Wishlists/Wishlists',[
            'wishlists'=> $wishlist,
        ]);

    }

    public function WishlistPost(Request $request){
       try {
            $customer_id = Auth::user()->profile->id;
            $product_id = $request->product_id;

            Wishlist::updateOrCreate([
                'customer_id' => $customer_id,
                'product_id' => $product_id,
            ], [
                'customer_id' => $customer_id,
                'product_id' => $product_id,
            ]);

            return back()->with('success', 'Product added to wishlist');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function WishlistDelete($id){
       $wishlist = Wishlist::findOrFail($id);
       $wishlist->delete();
       return back()->with('success','Wishlist remove success full');
    }



}
