<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

}
