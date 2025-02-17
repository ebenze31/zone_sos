<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZoneAgoraChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_agora_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('room_for')->nullable();
            $table->dateTime('time_start')->nullable();
            $table->string('member_in_room')->nullable();
            $table->integer('total_timemeet')->nullable();
            $table->integer('amount_meet')->nullable();
            $table->string('detail')->nullable();
            $table->string('sos_id')->nullable();
            $table->string('consult_doctor_id')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zone_agora_chats');
    }
}
