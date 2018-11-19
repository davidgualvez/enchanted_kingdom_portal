<?php

use Illuminate\Database\Seeder;
use App\GlobalSetting;

class GlobalSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $gs = new GlobalSetting;
        $gs->company_name 					= 'Enchanted Kingdom';
        $gs->amount_to_point_conversion 	= 100;
        $gs->save();
    }
}
