<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show(){
        $user = Auth::user(); 
        return view('pages.profile', compact('user'));
    }
}
