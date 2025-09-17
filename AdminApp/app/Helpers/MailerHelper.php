<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\verifyMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class MailerHelper {
    public static function sendOtp($to) {
        try {
            $otp = rand(1000, 9999);
            Mail::to($to)->send(new verifyMailer($otp));
            return $otp;
        } catch (\Throwable $th) {
            Log::error("OTP Mail Failed: " . $th->getMessage());
            return false;
        }
    }

     public static function sendOrderPlaceEmail($order, $userEmail){
        $paymentUrl = URL::route('payment.methods', [
            'payment_methods' => $order['payment_methods'],
            'payable' => $order['amount'],
            'vat' => $order['vat'],
            'total' => $order['total'],
            'order_id' => $order['id'],
        ]);

        Mail::send('emails.order-placed', [
            'order' => $order,
            'paymentUrl' => $paymentUrl,
        ], function ($message) use ($userEmail, $order) {
            $message->to($userEmail)->subject('Order Placed Successfully - #' . $order['order_number']);
        });
    }

    public static function sendPaymentStatusEmail($order, $userEmail)
    {
        Mail::send('emails.payment-status', [
            'order' => $order,
            'payment_status' => $order['payment_status'],
            'delivery_status' => $order['delivery_status'],
        ], function($message) use ($userEmail, $order) {
            $message->to($userEmail)->subject('Order Placed Successfully - #' . $order['order_number']);
        });
    }


    public static function sendOrderStatusEmail($order, $userEmail)
    {
        try {
            Mail::send('emails.order-status', [
                'order' => $order,
                'payment_status' => $order['payment_status'],
                'delivery_status' => $order['delivery_status'],
            ], function ($message) use ($userEmail, $order) {
                $message->to($userEmail)->subject('Order Status Update - #' . $order['order_number']);
            });
        } catch (\Throwable $th) {
            \Log::error('dd', [$th->getMessage()]);
        }
    }



    
}