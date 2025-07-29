<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MailerHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Exception;
use App\Models\CustomerProfile;

use function Termwind\render;

class UserAuthController extends Controller
{
    public function loginPage(){
        return Inertia::render('Frontend/UserAuth/Login');
    }

    public function registerPage(){
        return Inertia::render('Frontend/UserAuth/Register');
    }




    public function registerPost(Request $request){

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
   
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $customerProfile = CustomerProfile::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'cus_name' => $request->name,
            'ship_name' => $request->name,
        ]);

        $otp = MailerHelper::sendOtp($user->email);

         if ($otp !== false) {
            $user->update([
                'otp' => $otp,
            ]);
        } else {
            $user->delete();
            return redirect()->back()->with('error', 'OTP send fail, Try again');
        }


        return redirect()->route('UserVerify', [
            'email' => $user->email,
        ])->with('success', 'Registration success, please verify your email');

    }




    public function UserVerifyPage(Request $request){
        $email = $request->query('email');
        return Inertia::render('Frontend/UserAuth/Verify',[
             'email' => $email,
        ]);
    }


    public function UserOTPVerify(Request $request){
         $request->validate([
                'otp' => 'required|numeric|digits:4',
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Invalid OTP');
            }

            $user->otp = null;
            $user->save();

            Auth::login($user);

            return redirect('/')->with('success', 'Registration completed successfully');
    }





}
