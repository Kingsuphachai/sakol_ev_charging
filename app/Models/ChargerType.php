<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargerType extends Model
{
    protected $fillable = ['name', 'description'];

    public function stations()
    {
        return $this->belongsToMany(ChargingStation::class, 'station_charger_types', 'charger_type_id', 'station_id');
    }
}
