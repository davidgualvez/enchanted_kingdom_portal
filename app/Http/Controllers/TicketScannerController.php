<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketType;

class TicketScannerController extends Controller
{
    //
    public function showPage(Request $request){
        return view('pages.ticket.show');
    }

    public function checkTicket( Request $request){
        
        



        return back();
    }
}
