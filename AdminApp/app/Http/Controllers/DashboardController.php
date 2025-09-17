<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(){
        $total_orders = Invoice::count();
        $total_products = Product::count();
        $total_revenue = Invoice::where('delivery_status', 'Delivered')->sum('total');

        $TotalPayment = Invoice::where('payment_status', 'Success')
                      ->sum('payable');

        return Inertia::render('Dashboard',[
            'total_orders' => $total_orders,
            'total_products' => $total_products,
            'total_revenue' => $total_revenue,
            'TotalPayment' => $TotalPayment,
        ]);
    }
}
