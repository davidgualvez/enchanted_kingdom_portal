<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_header', function (Blueprint $table) {
            // $table->increments('id');
            $table->integer('sales_order_id');
            $table->integer('branch_id');
            $table->integer('customer_id')->nullable();
            $table->integer('transaction_type_id')->nullable();
            $table->decimal('total_amount', 18, 4)->nullable();
            $table->decimal('total_discount', 18, 4)->nullable();
            $table->decimal('net_amount', 18, 4)->nullable();
            $table->decimal('used_wallet', 18, 4)->nullable();
            $table->decimal('used_points', 18, 4)->nullable();
            $table->decimal('added_points', 18, 4)->nullable();
            $table->decimal('wallet_balance', 18, 4)->nullable();
            $table->decimal('points_balance', 18, 4)->nullable(); 
            $table->timestamps();
            $table->primary('sales_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_header');
    }
}
