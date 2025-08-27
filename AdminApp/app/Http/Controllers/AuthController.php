<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    public function LoginPage(){
        return inertia::render('Auth/Login');
    }

    public function login(Request $request){
         $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

         if (!Auth::attempt(['email'=> $email,'password'=> $password])) {
            return redirect()->back()->with('error','Invalid Email or Password !');
        }

         return redirect()->route('page.dashboard')->with('success','Login Successful');

    }

    
    public function loginOut(){

        Auth::logout();
        return redirect()->route('login.page')->with('success','Logout Successful !');

    }


}


