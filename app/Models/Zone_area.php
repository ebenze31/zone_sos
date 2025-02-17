<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone_area extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zone_areas';

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
    protected $fillable = ['name_zone_area', 'polygon_area', 'zone_partner_id', 'creator', 'check_send_to', 'group_line_id', 'last_edit_polygon_user_id', 'last_edit_polygon_time', 'old_polygon_area', 'old_edit_polygon_user_id'];

    
}
