<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $r = new Role;
        $r->name = "Administrator";
        $r->code = "A";
        $r->save();

        $r = new Role;
        $r->name = "Customer";
        $r->code = "C";
        $r->save();
    }
}
