<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HomeController extends Controller
{
    //
    public function index(){
        $now = \Carbon\Carbon::now();

        $dash = \App\Dashboard::where('start_at' , '<=', $now)
                    ->where('end_at', '>=', $now)
                    ->get(); 

        $dash->transform(function ($x){
            $url = Storage::url($x->image);
            $url = config('app.url').$url;
            return [
                'id' 			=> $x->id,
                'image' 		=> $url,
                'title' 		=> $x->title,
                'title_desc' 	=> $x->title_desc,
                'start_at' 		=> $x->start_at,
                'end_at' 		=> $x->end_at,
                'created_at' 	=> $x->created_at,
            ];
        });  

        // dd($dash);
        
        return view('pages.customers.home', compact('dash') );
    }
}
