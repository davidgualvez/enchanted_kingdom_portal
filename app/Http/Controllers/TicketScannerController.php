<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketType;
use DB;
use App\AppServices\Helper;

class TicketScannerController extends Controller
{
    //
    public function showPage(Request $request){
        return view('pages.ticket.show');
    }

    public function checkTicket( Request $request){
        

        $ticket = Ticket::where( DB::RAW('RTRIM( LTRIM(BARCODE) )'), $request->barcode)
            ->first();

        dd($ticket);


        return back();
    }
}
