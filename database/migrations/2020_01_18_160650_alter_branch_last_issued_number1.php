<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBranchLastIssuedNumber1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_last_issued_numbers', function (Blueprint $table) {
            //
            $table->integer('invoice_no')->nullable();
            $table->integer('none_invoice_no')->nullable();
            $table->integer('invoice_max_no')->nullable();
            $table->integer('invoice_max_counter')->nullable();
            $table->integer('transaction_no')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_last_issued_numbers', function (Blueprint $table) {
            //
            $table->dropColumns([
                'invoice_no',
                'none_invoice_no',
                'invoice_max_no',
                'invoice_max_counter',
                'transaction_no'
            ]);
        });
    }
}
