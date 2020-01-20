<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TransactType', function (Blueprint $table) {
            $table->integer('TRANSACTTYPEID');
            $table->text('DESCRIPTION');
            $table->timestamps();

            $table->primary('TRANSACTTYPEID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TransactType');
    }
}
