<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_data_officer_command extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_data_officer_commands';

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
    protected $fillable = ['name_officer_command', 'user_id', 'zone_partner_id', 'zone_area_id', 'officer_role', 'number', 'status', 'creator'];

    
}
