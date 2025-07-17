<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Inertia\Inertia;
use App\Models\Category;
use Exception;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = Category::latest()->get()->map(function ($category) {
                return [
                    'name' => $category->name,
                    'image' => $category->image,
                    'encrypted_id' => Crypt::encrypt($category->id),
                    'decrypted_id' => $category->id,
                ];
        });

        return Inertia::render('Categories/CategoryList',['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories/AddCategory');
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
                    $filePath = 'uploads/categories/'.$fileName;
                    $image->move(public_path('uploads/categories/'), $fileName);
                    $data['image'] = $filePath;
            }
            
            $Category = Category::create($data);

             return redirect()->route('categories.index')->with('success', 'Category'." "."{$Category->name}"." ".'Created successfully');
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
        $id = Crypt::decrypt($id);
       
        $category = Category::findOrFail($id);
        return Inertia::render('Categories/EditCategory',['category'=> $category]);
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

            
             $category = Category::findOrFail($id);

             $category->name = $request->name;
       
        if($request->hasFile('image')){
            
            if($category->image && file_exists(public_path($category->image))){
                unlink(public_path($category->image));
            }

            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]);

            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/categories/'.$fileName;

            $image->move(public_path('uploads/categories/'), $fileName);
            $category->image = $filePath;
        }

            $category->save();
            //-----Message Pass Category Name---
            return redirect()->route('categories.index')->with('success', 'Category'." "."{$category->name}"." ".'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encrypted_id)
    {
            $id = Crypt::decrypt($encrypted_id);
            $category = Category::findOrFail($id);

            if($category->image && file_exists(public_path($category->image))){
                unlink(public_path($category->image));
            }

            $category->delete();
             return redirect()->route('categories.index')->with('success', 'Category'." "."{$category->name}"." ".'Delete success');
    }
}
