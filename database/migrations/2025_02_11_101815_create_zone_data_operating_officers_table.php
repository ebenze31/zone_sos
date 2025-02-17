<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZoneDataOperatingOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_data_operating_officers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_officer')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('zone_operating_unit_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('status')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone_data_operating_officers');
    }
}
