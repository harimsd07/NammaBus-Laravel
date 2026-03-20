<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusDetail extends Model
{
    // Table name — explicitly set to match migration
    protected $table = 'bus_detail_tables';

    // Fix: timestamps default is true — removed the false override
    // Migration has timestamps() so this must be true
    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $fillable = [
        'busNameOrbusNo',
        'vehicle_no',
        'pick_up_stop',
        'destination',
        'pickup_time',
        'reach_destination_time',
        'latitude',
        'longitude',
        'driver_id', // Added — needed for driver-bus assignment in v2
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    // Relationship: bus belongs to a driver (User)
    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id');
    }
}
