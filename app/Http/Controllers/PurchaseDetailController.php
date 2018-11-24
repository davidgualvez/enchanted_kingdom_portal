<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator; 
use Carbon\Carbon;
use App\Part; 
use App\Purchase;
use App\PurchaseDetail;
use DB;
use App\Transformers\ActivePurchaseTransformer;

class PurchaseDetailController extends Controller
{
    // 
    public function active(Request $request){
        $now  = Carbon::now();
        $user = Auth::user();
        //dd($user->customer->id, $now);
        $pd = PurchaseDetail::where('CUSTOMERCODE', $user->customer->id)
        		->where('valid_at','>=', $now)
        		->where('qty_remaining','>',0)
        		->get();

        $apt 	= new ActivePurchaseTransformer;
        $nvalue = $apt->activePurchase($pd);

        return response()->json([
        	'success' 	=> true,
        	'status' 	=> 200,
        	'data' 		=> $nvalue
        ]);
    }
}
