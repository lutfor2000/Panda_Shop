<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
  public function profile(){
      return Inertia::render('Frontend/Dashboard/profile');
  }
}
