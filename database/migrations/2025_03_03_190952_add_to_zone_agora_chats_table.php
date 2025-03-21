<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToZoneAgoraChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zone_agora_chats', function (Blueprint $table) {
            $table->string('than_2_people_timemeet')->nullable();
            $table->string('than_2_people_time_start')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zone_agora_chats', function (Blueprint $table) {
            //
        });
    }
}
