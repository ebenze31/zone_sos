<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_data_operating_unit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_data_operating_units';

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
    protected $fillable = ['name', 'zone_partner_id', 'zone_area_id'];

    
}
