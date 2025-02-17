<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_data_operating_officer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_data_operating_officers';

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
    protected $fillable = ['name_officer', 'lat', 'lng', 'zone_operating_unit_id', 'user_id', 'status'];

    
}
