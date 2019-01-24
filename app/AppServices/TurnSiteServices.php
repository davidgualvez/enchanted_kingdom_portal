<?php 

namespace App\AppServices;

use App\TurnSite;
use Carbon\Carbon;
use App\AppServices\Helper;

class TurnSiteServices{

    private $turn_site;
 
    private $created_at_clarion_date;
    private $created_at_clarion_time;
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
    //----------
    private $startOfTheDay;
    
    public function __construct(){
        
        $this->station_code = config('app.branch_id');
        $this->shift        = config('app.shift');
        $this->employee_number  = config('app.employee_number');
        $this->pos_number       = config('app.pos_number');
        $this->type_number      = config('app.type_number');
        $this->created_at       = Carbon::now();

        $helper = new Helper;
        $this->created_at_clarion_date = $helper->getClarionDate($this->created_at);
        $this->created_at_clarion_time = $helper->getClarionTime($this->created_at);


        $this->startOfTheDay = Carbon::create(
            $this->created_at->year, 
            $this->created_at->month, 
            $this->created_at->day, 5, 59, 59);
    }

    public function findOrCreate(){

        // read if there is an existing record
        $turn_site = TurnSite::where('station_code', $this->station_code)
            ->where('pos_number', $this->pos_number) 
            ->orderBy('created_at', 'desc')
            ->first();

        // if FALSE | NULL
        if(!$turn_site){  
            $newTs = $this->newRecord();
            return $newTs;
        }

        // if now is less than
        $nd = Carbon::now()->addDay();
        $nextDay = Carbon::create(
            $nd->year,
            $nd->month,
            $nd->day,
            6,
            0,
            0
        );

        // if ts date is less than today
        if( $turn_site->created_at < $this->startOfTheDay){
            //dd('PREV DAY'); 
            if($this->created_at < $this->startOfTheDay){
                // dd('USE RECORD OF PREV');
                return $turn_site;
            }

            if($this->created_at > $this->startOfTheDay){
                // dd('NEW RECORD');
                $newTs = $this->newRecord();
                return $newTs;
                
            }
        }

        //
        if( $this->startOfTheDay <= $turn_site->created_at && $nextDay >= $turn_site->created_at){
            return $turn_site;
        }
        
        return $turn_site;
    }   

    private function newRecord(){
        $lastRecord = $this->getLastRecord(); 
        $now = Carbon::now();

        $newTs = new TurnSite;
        $newTs->station_code        = $this->station_code;
        $newTs->turn_over_id        = $lastRecord->turn_over_id + 1;
        $newTs->clarion_date_now    = $this->created_at_clarion_date;
        $newTs->clarion_entry_date  = $this->created_at_clarion_date;
        $newTs->clarion_entry_time  = $this->created_at_clarion_time;
        $newTs->SQLDATE             = $this->created_at;
        $newTs->shift               = $this->shift;
        $newTs->employee_number     = $this->employee_number;
        $newTs->pos_number          = $this->pos_number;
        $newTs->type_number         = (double)99.0; 
        
        $newTs->total_sales  = 0;
        $newTs->po           = 0;
        $newTs->prepay       = 0;
        $newTs->save();  
        return $newTs;
    }

    public function getClarionTime(){
        return $this->created_at_clarion_time;
    }

    private function getLastRecord(){

        $turn_site = TurnSite::where('station_code', $this->station_code)
            ->orderBy('turn_over_id', 'desc')
            ->first(); 
        return $turn_site;

    }

}