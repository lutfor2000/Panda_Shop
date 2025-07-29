<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\verifyMailer;
use Illuminate\Support\Facades\Log;

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
}