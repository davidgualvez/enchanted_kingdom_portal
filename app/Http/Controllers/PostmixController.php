<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Postmix;

class PostmixController extends Controller
{
    //
    public function show($pid){
        
        

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'Success'
        ]);
    }
}
