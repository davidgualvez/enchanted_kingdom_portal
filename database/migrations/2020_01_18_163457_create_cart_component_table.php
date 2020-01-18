<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts_component', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id');
            $table->integer('base_product_id');
            $table->integer('product_id');
            $table->integer('base_qty');
            $table->integer('qty');
            $table->double('price');
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
        Schema::dropIfExists('carts_component');
    }
}
