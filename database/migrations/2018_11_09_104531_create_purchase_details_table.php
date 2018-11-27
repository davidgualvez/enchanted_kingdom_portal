<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_id');
            $table->integer('part_id'); 
            $table->integer('customer_id');
            $table->integer('product_promotion_id')->nullable();
            $table->text('description');
            $table->integer('qty');
            $table->double('srp',10,2); 
            $table->double('amount',10,2);
            $table->integer('discount_ispercent')->nullable();
            $table->double('discount_rate')->nullable();
            $table->double('discount_amount',10,2);
            $table->double('net_amount',10,2);
            $table->string('status')->comment = "P=Pending, A=Activated";
            $table->date('valid_until');
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
        Schema::dropIfExists('purchase_details');
    }
}
