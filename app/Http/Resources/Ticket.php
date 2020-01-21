<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Ticket extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'type' => $this->TYPE,
            'barcode' => trim($this->BARCODE),
            'ticket_no' => $this->TICKETNO,
            'purchased_at' => (int)$this->PURCHASED, 
            'expired_at' => (int)$this->EXPIRY,
            'status' => strtolower(trim($this->STATUS)),
            'balance' => (double)$this->BALANCE,
            'date_today' => getClarionDate(now())
        ];
    }
}
