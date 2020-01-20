<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEarmPointTransaction1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earn_point_transactions', function (Blueprint $table) {
            //
            $table->renameColumn('purchase_id', 'transaction_id');
            $table->integer('customer_id');
            $table->integer('transaction_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('earn_point_transactions', function (Blueprint $table) {
            //
            $table->renameColumn('transaction_id', 'purchase_id');
            $table->dropColumn(['customer_id','transaction_type_id']);
        });
    }
}
