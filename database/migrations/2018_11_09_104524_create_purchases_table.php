<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->double('gross_amount',10,2)->nullable();
            $table->double('total_discount',10,2)->nullable();
            $table->double('net_amount',10,2)->nullable();
            $table->string('type')->comment = "web=Web Transaction, pos=POS Transaction";
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
        Schema::dropIfExists('purchases');
    }
}
