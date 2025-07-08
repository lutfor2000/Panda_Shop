<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Exception;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return Inertia::render('Brands/BrandList',['brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Brands/AddBrand');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try{ //<--error check start

            //--Validation Check-----
           $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = [
                    'name' => $request->name,
            ];

            //Image Upload---
            if($request->hasFile('image')){
                    $image = $request->file('image');
                    $fileName = time().'.'.$image->getClientOriginalExtension();
                    $filePath = 'uploads/brands/'.$fileName;
                    $image->move(public_path('uploads/brands/'), $fileName);
                    $data['image'] = $filePath;
            }
            
            Brand::create($data);

             return redirect()->route('brands.index')->with('success', 'Brand created successfully');
        }
        catch(Exception $th){

            return redirect()->back()->with('error', $th->getMessage());

        }//<--error Check






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
        $brand = Brand::findOrFail($id);
        return Inertia::render('Brands/EditBrand',['brand' => $brand]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


             $brand = Brand::findOrFail($id);

             $brand->name = $request->name;
       
        if($request->hasFile('image')){
            
            if($brand->image && file_exists(public_path($brand->image))){
                unlink(public_path($brand->image));
            }

            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]);

            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/brands/'.$fileName;

            $image->move(public_path('uploads/brands/'), $fileName);
            $brand->image = $filePath;
        }

            $brand->save();

            return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            $brand = Brand::findOrFail($id);

            if($brand->image && file_exists(public_path($brand->image))){
                unlink(public_path($brand->image));
            }

            $brand->delete();
             return redirect()->route('brands.index')->with('success', 'Brand Delete successfully');
    }
}
