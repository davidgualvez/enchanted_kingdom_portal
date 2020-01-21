<?php 


function getClarionDate(\Carbon\Carbon $date){
    $start_date = '1801-01-01';
    $start_from = \Carbon\Carbon::parse($start_date);
    $diff = $date->diffInDays($start_from) + 4;
    return $diff;
}