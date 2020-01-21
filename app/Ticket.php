<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Ticket extends Model
{
    //
    protected $table = 'Tickets';
    public $incrementing = false;
    protected $connection = 'sqlsrv2';

    //model mapping 
    protected $maps = [
        'type' => 'TYPE',
        'barcode' => 'BARCODE',
        'ticket_no' => 'TICKETNO',
        'purchased_at' => 'PURCHASED',
        'expired_at' => 'EXPIRY'
    ];

    public function ticketType(){
        return $this->belongsTo('App\TicketType','type');
    }
}
