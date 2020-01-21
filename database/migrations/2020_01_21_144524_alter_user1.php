<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUser1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_users', function (Blueprint $table) {
            //
            // $table->string('provider_id')->nullable()->unique(false)->change();
            $table->dropUnique('web_users_provider_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_users', function (Blueprint $table) {
            //
            // $table->string('provider_id')->nullable()->unique()->change();
        });
    }
}
