<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        //
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
}
