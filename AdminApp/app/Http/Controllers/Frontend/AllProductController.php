<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ProductReview;
use App\Models\InvoiceProduct;
use App\Models\Invoice;

class AllProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input("search");
        $categories = $request->input("categories");
        $brands = $request->input("brands");
        $min_price = $request->input("min_price");
        $max_price = $request->input("max_price");


        $products = Product::query();

         if (!empty($search)) {
            $products = $products->where("title", "like", "%$search%");
         }

        if (!empty($categories)) {
            $products = $products->whereIn("category_id", $categories);
        }

        if (!empty($brands)) {
            $products = $products->whereIn("brand_id", $brands);
        }

        if (!empty($min_price)) {
            $products = $products->where(function ($query) use ($min_price) {
                $query->where("price", ">=", $min_price)->orWhere(function ($query) use ($min_price) {
                    $query->where('is_discount', true)->where("discount_price", ">=", $min_price);
                });
            });
        }


         if (!empty($max_price)) {
            $products = $products->where(function ($query) use ($max_price) {
                $query->where("price", "<=", $max_price)->orWhere(function ($query) use ($max_price) {
                    $query->where('is_discount', true)->where("discount_price", "<=", $max_price);
                });
            });
        }

      
        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        return Inertia::render('Frontend/AllProducts/ProductPage',[
            'products' => $products->get()->load('details'),
            'categories' => $categories,
            'brands' => $brands,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    $product = Product::findOrFail($id)->load('category','brand','details');
            $related_products = Product::with('details')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();

            $reviews = ProductReview::where('product_id', $id)->with('customer')->orderBy('created_at','desc')->get()->map(function($review){
                return [
                    'customer_name' => $review->customer->cus_name,
                    'description' => $review->description,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at->format('Y-m-d'),
                ];
            });


            $is_on_wishlist = false;
            $can_review = false;

        if(Auth::check() && Auth::user() ){
            $user = Auth::user();
            $is_on_wishlist = $user->profile->wishlists->contains('product_id', $product->id);

    
            // $can_review = InvoiceProduct::whereHas('invoice', function ($query) use ($user) {
            //     $query->where('customer_id', $user->profile->id)->where('delivery_status', 'Delivered');
            // })->where('product_id', $product->id)->exists();
        }
          
        return Inertia::render('Frontend/AllProducts/ProductDetailsPage',[
            'product'=>$product,
            'is_on_wishlist'=>$is_on_wishlist,
            'related_products'=>$related_products,
            'reviews'=>$reviews,
            // 'can_review'=>$can_review,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addReview(Request $request){
      $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string|max:200',
            'rating' => 'required|integer|min:1|max:5',
      ]);

      $user = Auth::user();
      $productId = $request->product_id;

      ProductReview::updateOrCreate([
            'product_id' => $productId,
            'customer_id' => $user->profile->id],
    [
           'description' => $request->description,
           'rating' => $request->rating

      ]);

      return redirect()->back()->with('success','Review added successfully');

    }


}
