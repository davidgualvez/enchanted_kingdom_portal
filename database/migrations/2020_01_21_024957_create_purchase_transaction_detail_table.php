<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_transaction_id');
            $table->integer('sequence');
            $table->integer('sitepart_id');
            $table->string('product_name');
            $table->double('cost');
            $table->double('retail_price');
            $table->double('qty');
            $table->integer('is_postmix')->nullable();
            $table->integer('is_vat')->nullable();
            $table->integer('is_food')->nullable();
            $table->double('vatable_sales')->nullable();
            $table->double('vat_exempt_sales')->nullable(); 
            $table->double('vat_zerorated_sales')->nullable();
            $table->double('zerorated_vat_amount')->nullable();
            $table->double('vat_amount')->nullable();
            $table->double('r_vat_amount')->nullable();
            $table->integer('is_admission')->nullable();
            $table->double('admission_sales')->nullable();
            $table->double('amusement_tax_amount')->nullable();
            $table->double('r_amusement_tax_amount')->nullable();
            $table->double('gross_amount')->nullable();
            $table->double('discount_rate')->nullable();
            $table->double('discount_value')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('net_amount')->nullable();
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
        Schema::dropIfExists('purchase_transaction_details');
    }
}
