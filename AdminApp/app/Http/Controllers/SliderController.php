<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use App\Models\Slider;


class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
         $sliders = Slider::latest()->get()->map(function ($sliders) {
                return [
                    'active' => $sliders->active,
                    'image' => $sliders->image,
                    'encrypted_id' => Crypt::encrypt($sliders->id),
                ];
        });
        return Inertia::render('Sliders/SliderList',['sliders' => $sliders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Sliders/AddSlider');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try{ //<--error check start

            //--Validation Check-----
           $request->validate([
                'active' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

             $data = [
                    'active' => $request->active,
            ];

            //Image Upload---
            if($request->hasFile('image')){
                    $image = $request->file('image');
                    $fileName = time().'.'.$image->getClientOriginalExtension();
                    $filePath = 'uploads/sliders/'.$fileName;
                    $image->move(public_path('uploads/sliders/'), $fileName);
                    $data['image'] = $filePath;
            }
            
           $slider = Slider::create($data);
           

             return redirect()->route('sliders.index')->with('success', "Slide"." "."{$slider->active}"." "."created successfully");
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
       

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $id = Crypt::decrypt($id);
        $slider = Slider::findOrFail($id);
         return Inertia::render('Sliders/EditSlider',['slider' => $slider]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $request->validate([
                'active' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


             $slider = Slider::findOrFail($id);

             $slider->active = $request->active;
       
        if($request->hasFile('image')){
            
            if($slider->image && file_exists(public_path($slider->image))){
                unlink(public_path($slider->image));
            }

            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]);

            $image = $request->file('image');

            $fileName = time().'.'.$image->getClientOriginalExtension();
            $filePath = 'uploads/sliders/'.$fileName;

            $image->move(public_path('uploads/sliders/'), $fileName);
            $slider->image = $filePath;
        }

            $slider->save();

            return redirect()->route('sliders.index')->with('success', "Slider update successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encrypted_id)
    {
          $id = Crypt::decrypt($encrypted_id);
            $slider = Slider::findOrFail($id);

            if($slider->image && file_exists(public_path($slider->image))){
                unlink(public_path($slider->image));
            }

            $slider->delete();
            
            return redirect()->route('sliders.index')->with('success', "Sliders ID"." "."{$slider->id}"." "."delete successfully");
    }
}
