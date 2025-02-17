<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_agora_chat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_agora_chats';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['room_for', 'time_start', 'member_in_room', 'total_timemeet', 'amount_meet', 'detail', 'sos_id', 'consult_doctor_id'];

    
}
