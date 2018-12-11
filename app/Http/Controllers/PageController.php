<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function store(){
    	return view('pages.customers.products');
    }

    public function thingsToDo(){
    	return view('pages.customers.things-to-do');
    }

    public function siteMap(){
    	return view('pages.customers.site-map');
    }

    public function todaySpecial(){
        return view('pages.customers.today_specials');
    }
}
