<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_partner extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_partners';

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
    protected $fillable = ['name', 'full_name', 'phone', 'type_partner', 'mail', 'logo', 'color_ci_1', 'color_ci_2', 'color_ci_3', 'province', 'district', 'sub_district', 'sub_area', 'show_homepage','max_polygon_area'];

    
}
