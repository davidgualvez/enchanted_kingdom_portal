<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchLastIssuedNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_last_issued_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id');
            $table->integer('order_slip_header_no');
            $table->integer('order_slip_detail_no');
            $table->integer('sales_order_header_no');
            $table->integer('sales_order_details_no');
            $table->integer('customer_no');
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
        Schema::dropIfExists('branch_last_issued_numbers');
    }
}
