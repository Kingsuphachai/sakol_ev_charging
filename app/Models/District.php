<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'province_name', 'zipcode'];

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function stations()
    {
        return $this->hasMany(ChargingStation::class);
    }
}
