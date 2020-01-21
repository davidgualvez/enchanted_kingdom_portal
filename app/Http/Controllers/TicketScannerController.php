<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\TicketType;
use DB;
use App\AppServices\Helper;
use App\Http\Resources\Ticket as TicketResource;

class TicketScannerController extends Controller
{
    //
    public function showPage(Request $request){
        return view('pages.ticket.show');
    }

    public function checkTicket( Request $request){
        

        $ticket = Ticket::where( DB::RAW('RTRIM( LTRIM(BARCODE) )'), $request->barcode)
            ->first();

        if( !$ticket ){
            return response()->json([
                'success' => false,
                'message' => 'Ticket Not Found'
            ]);
        }
  
        return response()->json([
            'success' => true,
            'message' => 'Ticket Found',
            'data' => new TicketResource($ticket)
        ]);
    }
}
