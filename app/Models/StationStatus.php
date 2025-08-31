<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationStatus extends Model
{
    protected $fillable = ['name', 'description', 'is_visible', 'icon_path'];

    public function stations()
    {
        return $this->hasMany(ChargingStation::class, 'status_id');
    }
}
