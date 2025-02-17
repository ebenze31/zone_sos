<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZoneDataOfficerCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_data_officer_commands', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_officer_command')->nullable();
            $table->string('user_id')->nullable();
            $table->string('zone_area_id')->nullable();
            $table->string('officer_role')->nullable();
            $table->string('number')->nullable();
            $table->string('status')->nullable();
            $table->string('creator')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone_data_officer_commands');
    }
}
