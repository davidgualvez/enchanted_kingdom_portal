<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable()->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_subscribe')->default(false)->comment = "0=false, 1=true";
            $table->string('mobile_number')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable(); 
            $table->string('token')->nullable(); 
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
