<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_id');
            $table->integer('transaction_no');
            $table->integer('invoice_no');
            $table->integer('customer_id');
            $table->string('customer_code')->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('customer_type')->nullable();
            $table->string('customer_id_number')->nullable();
            $table->string('customer_address')->nullable();
            $table->double('gross_total')->nullable();
            $table->double('scpwd_discount')->nullable();
            $table->double('net_total')->nullable();
            $table->double('vatable_sales_total')->nullable();
            $table->double('vat_exempt_sales_total')->nullable(); 
            $table->double('vat_zerorated_sales_total')->nullable();
            $table->double('vat_amount_total')->nullable();
            $table->double('r_vat_amount_total')->nullable();
            $table->double('admission_sales_total')->nullable();
            $table->double('amusement_tax_amount_total')->nullable();
            $table->double('r_amusement_tax_amount_total')->nullable();
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
        Schema::dropIfExists('purchase_transactions');
    }
}
