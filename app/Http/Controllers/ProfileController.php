<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    //
    public function show(){
        $user = Auth::user(); 
        return view('pages.profile', compact('user'));
    }
}
