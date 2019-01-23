<?php 

namespace App\AppServices;

use Carbon\Carbon; 

class Helper
{  
    public function getClarionDate(Carbon $date){
        $start_date = '1801-01-01';
        $start_from = Carbon::parse($start_date);
        $diff = $date->diffInDays($start_from) + 4;
        return $diff;
    }

}