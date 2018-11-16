<?php

use Illuminate\Database\Seeder;
use App\AdjustmentReason;

class AdjustmentReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $war = new AdjustmentReason;
        $war->name = 'mema lang'; 
        $war->save();
    }
}
