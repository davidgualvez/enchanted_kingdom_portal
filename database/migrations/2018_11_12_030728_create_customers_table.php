<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.  
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_customers', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('user_id');
            $table->string('full_name');
            $table->double('points')->default(0);
            $table->double('wallet')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
