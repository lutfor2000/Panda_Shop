<?php

namespace App\Helpers;

use Mail;
use App\Mail\verifyMailer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceHelper {

     public static function generatePDF($order)
    {
        // Pdf facade Use Blade view load
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
        ]);

        // File Name and path
        $fileName = 'invoice-' . $order['order_number'] . '.pdf';
        $path = 'invoices/' . $fileName;

        // PDF save -> storage/app/public/invoices 

        Storage::put('public/' . $path, $pdf->output());

        return $path;
    }

    //Install-cmd-> composer require barryvdh/laravel-dompdf


}