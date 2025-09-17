<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           $products = Product::all()->load('category','brand')->map(function ($product) {
                return [
                    'title' => $product->title,
                    'image' => $product->image,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category_name' => $product->category->name ?? null,
                    'brand_name' => $product->brand->name ?? null,
                    'encrypted_id' => Crypt::encrypt($product->id),
                ];
        });
        return Inertia::render('Products/ProductList',['products'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return Inertia::render('Products/AddProduct',['brands' => $brands, 'categories' => $categories]);
    }


//==========Image Upload=============================

// private function handleImage($newImage, $oldImagePath = null)
// {
//     if ($newImage) {
        
//         if ($oldImagePath) {
//             $oldFullPath = public_path($oldImagePath);
//             if (file_exists($oldFullPath)) {
//                 unlink($oldFullPath);
//             }
//         }

    
//         $fileName = time() . '.' . $newImage->getClientOriginalExtension();
//         $filePath = 'uploads/products/' . $fileName;
//         $newImage->move(public_path('uploads/products/'), $fileName);
//         return $filePath;
//     }

//     return $oldImagePath;
// }


    private function handleImage($image, $oldUrl = null)
        {
            $imageLocation = $oldUrl;
            if ($image) {
                $imageName = time() . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/products/');
                
                $image->move($destinationPath, $imageName);

                $imageLocation = 'uploads/products/' . $imageName;
            }
             return $imageLocation;
        }
   

    public function store(Request $request)
    {
        try {

             $request->validate([
                'title' => 'required|string|max:255',
                'short_des' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'is_discount' => 'required|boolean|in:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'in_stock' => 'required|boolean|in:0,1',
                'stock' => 'required|integer',
                'remark' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'brand' => 'required|exists:brands,id',
                'img1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'color' => 'required|string|max:255',
                'size' => 'required|string|max:255',
            ]);


            $product = [
                'title' => $request->title,
                'short_des' => $request->short_des,
                'price' => $request->price,
                'is_discount' => $request->is_discount,
                'discount_price' => $request->discount_price,
                'image' => $this->handleImage($request->file('image')),
                'in_stock' => $request->in_stock,
                'stock' => $request->stock,
                'remark' => $request->remark,
                'category_id' => $request->category,
                'brand_id' => $request->brand,
            ];

             $product = Product::create($product);


            $productDetails = [
                'description' => $request->description,
                'color' => $request->color,
                'size' => $request->size,
                'img1' => $this->handleImage($request->file('img1')),
                'img2' => $this->handleImage($request->file('img2')),
                'img3' => $this->handleImage($request->file('img3')),
                'img4' => $this->handleImage($request->file('img4')),
                'product_id' => $product->id,
            ];


             ProductDetail::create($productDetails);

            return redirect()->route('products.index')->with('success', 'Product created successfully');

        } catch (\Throwable $th) {
           return back()->with('error', $th->getMessage());
        }
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
            $id = Crypt::decrypt($id);
            $brands = Brand::latest()->get();
            $categories = Category::latest()->get();
            $product = Product::findOrFail($id)->load('details');
            return Inertia::render('Products/EditProduct',['brands'=>$brands,'categories'=>$categories,'product'=>$product]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'short_des' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'is_discount' => 'required|boolean|in:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'in_stock' => 'required|boolean|in:0,1',
                'stock' => 'required|integer',
                'remark' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'brand' => 'required|exists:brands,id',
                'img1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'img4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'color' => 'required|string|max:255',
                'size' => 'required|string|max:255',
            ]);

            $product = Product::findOrFail($id);
            $productDetail = ProductDetail::where('product_id', $product->id)->first();


            //======Old Image Select===============
            $oldMainImage = $product->image;
            $oldImg1 = $productDetail->img1;
            $oldImg2 = $productDetail->img2;
            $oldImg3 = $productDetail->img3;
            $oldImg4 = $productDetail->img4;

            //=======Product Update===============
             $product->update([
                'title' => $request->title,
                'short_des' => $request->short_des,
                'price' => $request->price,
                'is_discount' => $request->is_discount,
                'discount_price' => $request->discount_price,
                'image' => $this->handleImage($request->file('image'), $product->image),
                'in_stock' => $request->in_stock,
                'stock' => $request->stock,
                'remark' => $request->remark,
                'category_id' => $request->category,
                'brand_id' => $request->brand,
            ]);

            //=======productDetail Update===============
             $productDetail->update([
                'description' => $request->description,
                'color' => $request->color,
                'size' => $request->size,
                'img1' => $this->handleImage($request->file('img1'), $productDetail->img1),
                'img2' => $this->handleImage($request->file('img2'), $productDetail->img2),
                'img3' => $this->handleImage($request->file('img3'), $productDetail->img3),
                'img4' => $this->handleImage($request->file('img4'), $productDetail->img4),
                'product_id' => $product->id,
            ]);

            //==============Old Image Delete==========================
            if ($request->hasFile('image')) $this->handleImageDelete($oldMainImage);
            if ($request->hasFile('img1')) $this->handleImageDelete($oldImg1);
            if ($request->hasFile('img2')) $this->handleImageDelete($oldImg2);
            if ($request->hasFile('img3')) $this->handleImageDelete($oldImg3);
            if ($request->hasFile('img4')) $this->handleImageDelete($oldImg4);

             return redirect()->route('products.index')->with('success', 'Product Update successfully');

        } catch (\Throwable $th) {
             return back()->with('error', $th->getMessage());
        }
    }




    //==================Delete Image========
    private function handleImageDelete($image){
        if ($image) {
            $imagePath = public_path($image); // full path: public/uploads/products/filename.jpg
            if (file_exists($imagePath)) {
                unlink($imagePath); // delete the file
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id =Crypt::decrypt($id);
        $product = Product::find($id);
        $productDetail = ProductDetail::where('product_id', $product->id)->first();

        $this->handleImageDelete($product->image);
        $this->handleImageDelete($productDetail->img1);
        $this->handleImageDelete($productDetail->img2);
        $this->handleImageDelete($productDetail->img3);
        $this->handleImageDelete($productDetail->img4);

        $productDetail->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
