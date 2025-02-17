<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZoneAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_zone_area')->nullable();
            $table->string('polygon_area')->nullable();
            $table->string('zone_partner_id')->nullable();
            $table->string('creator')->nullable();
            $table->string('check_send_to')->nullable();
            $table->string('group_line_id')->nullable();
            $table->string('last_edit_polygon_user_id')->nullable();
            $table->string('last_edit_polygon_time')->nullable();
            $table->string('old_polygon_area')->nullable();
            $table->string('old_edit_polygon_user_id')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone_areas');
    }
}
