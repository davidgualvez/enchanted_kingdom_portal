<?php 

namespace App\AppServices;

use App\TurnSite;
use Carbon\Carbon;

class TurnSiteServices{

    private $turn_site;
 
    private $created_at_clarion;
    private $created_at;
    private $total_sales;
    private $po;
    private $prepaid;
    //-------- 
    private $turn_over_id;
    private $station_code; 
    private $shift;
    private $employee_number;
    private $pos_number;
    private $type_number;

    public function __construct(){
        
        $this->station_code = config('app.branch_id');
        $this->shift        = config('app.shift');
        $this->employee_number  = config('app.employee_number');
        $this->pos_number       = config('app.pos_number');
        $this->type_number      = config('app.type_number');
        $this->created_at       = Carbon::now();

        $ts = TurnSite::where(
            'created_at', $this->created_at
            )
            ->orderBy('created_at', 'desc')
            ->first();
        
        return $ts;
    }



}