<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\Storage;
use App\Helpers\InvoiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return Inertia::render('Frontend/Dashboard/UserDashboard',);
         }

          $orders = Invoice::where('customer_id', $profile->id)->orderBy('created_at', 'desc')->get()->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'order_number' => '#' . $invoice->id,
                'date' => $invoice->created_at->format('Y-m-d'),
                'amount' => number_format($invoice->payable, 2),
                'payment_status' => $invoice->payment_status,
                'delivery_status' => $invoice->delivery_status,
                'customer_details' => json_decode($invoice->cus_details, true),
                'shipping_details' => json_decode($invoice->ship_details, true),
                'products' => $invoice->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->product->title,
                        'quantity' => $product->quantity,
                        'size' => $product->size,
                        'color' => $product->color,
                        'price' => number_format($product->price, 2),
                        'total' => number_format($product->price * $product->quantity, 2),
                    ];
                })
            ];
        });

        return Inertia::render('Frontend/Dashboard/UserDashboard', [
            'user' => $user,
            'profile' => $profile,
            'orders' => $orders,
        ]);
    }


    public function orderDetails($id){
        $user = Auth::user();
        $invoice = Invoice::where('customer_id', $user->profile->id)->where('id', $id)->firstOrFail();

        $orderDetails = [
            'id' => $invoice->id,
            'order_number' => '#' . $invoice->id,
            'date' => $invoice->created_at->format('Y-m-d'),
            'amount' => number_format($invoice->payable, 2),
            'payment_status' => $invoice->payment_status,
            'delivery_status' => $invoice->delivery_status,
            'customer_details' => json_decode($invoice->cus_details, true),
            'shipping_details' => json_decode($invoice->ship_details, true),
            'products' => $invoice->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->product->title,
                    'quantity' => $product->quantity,
                    'size' => $product->size,
                    'color' => $product->color,
                   'image' => $product->product->image,
                    'price' => number_format($product->product->discount_price, 2),
                    'total' => number_format($product->product->discount_price * $product->quantity, 2),
                ];
            }),
        ];

       return Inertia::render('OrderDetails',[
        'order' => $orderDetails
       ]);
    }

    
  //============User Pdf File Download Part Start=================================
    public function downloadInvoice($id){
        $user = Auth::user();
        $invoice = Invoice::where('customer_id', $user->profile->id)->where('id', $id)->firstOrFail();

        $orderDetails = [
            'id' => $invoice->id,
            'order_number' => '#' . $invoice->id,
            'date' => $invoice->created_at->format('Y-m-d'),
            'amount' => number_format($invoice->payable, 2),
            'payment_status' => $invoice->payment_status,
            'delivery_status' => $invoice->delivery_status,
            'customer_details' => json_decode($invoice->cus_details, true),
            'shipping_details' => json_decode($invoice->ship_details, true),
            'products' => $invoice->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->product->title,
                    'quantity' => $product->quantity,
                    'size' => $product->size,
                    'color' => $product->color,
                    'price' => number_format($product->price, 2),
                    'total' => number_format($product->price * $product->quantity, 2),
                ];
            }),
        ];

    // Generate PDF using InvoiceHelper
    $path = InvoiceHelper::generatePDF($orderDetails);

    // Download PDF
    return Storage::download('public/' . $path, 'invoice-' . $invoice->id . '.pdf');

    }

//============User Pdf Download Part End=================================

//============User Pdf DeleteInvoice Part Start=================================

    public function DeleteInvoice($id){

        $invoice = Invoice::find($id);
        $InvoiceProduct = InvoiceProduct::where('invoice_id', $invoice->id);
                       
        $InvoiceProduct->delete();
        $invoice->delete(); 

         return redirect()->back()->with('success', 'Invoice Delete successfully');
    }




    public function profile(Request $request){
        $request->validate([
            'cus_name' => 'required|string',
            'cus_add' => 'required|string',
            'cus_city' => 'required|string',
            'cus_state' => 'required|string',
            'cus_postcode' => 'required|string',
            'cus_country' => 'required|string',
            'cus_phone' => 'required|string',
            'cus_fax' => 'required|string',
            'ship_name' => 'required|string',
            'ship_add' => 'required|string',
            'ship_city' => 'required|string',
            'ship_state' => 'required|string',
            'ship_postcode' => 'required|string',
            'ship_country' => 'required|string',
            'ship_phone' => 'required|string'
        ]);

        $user = Auth::user();
        $profile = $user->profile;

         $profile->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'cus_name' => $request->cus_name,
            'cus_add' => $request->cus_add,
            'cus_city' => $request->cus_city,
            'cus_state' => $request->cus_state,
            'cus_postcode' => $request->cus_postcode,
            'cus_country' => $request->cus_country,
            'cus_phone' => $request->cus_phone,
            'cus_fax' => $request->cus_fax,
            'ship_name' => $request->ship_name,
            'ship_add' => $request->ship_add,
            'ship_city' => $request->ship_city,
            'ship_state' => $request->ship_state,
            'ship_postcode' => $request->ship_postcode,
            'ship_country' => $request->ship_country,
            'ship_phone' => $request->ship_phone
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
        
    }

    public function profileMail(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = Auth::user();

        $isExist = User::where('email', $request->email)->where('id', '!=', $user->id)->exists();

        if ($isExist) {
            return redirect()->back()->with('error', 'Email is already taken');
        }

        $user->email = $request->email;
        $user->save();
        return redirect()->back()->with('success', 'Email updated successfully');
    
    }

    public function profilePassword(Request $request){
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->current_password,$user->password)){
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        $user->password = $request->password;
        $user->save();

         return redirect()->back()->with('success', 'Password updated successfully');
    }

    
}
