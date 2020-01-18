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
            $table->double('invoice_no',18)->default(0)->nullable();
            $table->double('none_invoice_no',18)->default(0)->nullable();
            $table->double('invoice_max_no',18)->default(0)->nullable();
            $table->integer('invoice_max_counter')->default(0)->nullable();
            $table->double('transaction_no', 18)->default(0)->nullable();

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
