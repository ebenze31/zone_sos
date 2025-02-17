<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZonePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('type_partner')->nullable();
            $table->string('group_line_id')->nullable();
            $table->string('mail')->nullable();
            $table->string('logo')->nullable();
            $table->string('color_ci_1')->nullable();
            $table->string('color_ci_2')->nullable();
            $table->string('color_ci_3')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('sub_area')->nullable();
            $table->string('show_homepage')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone_partners');
    }
}
