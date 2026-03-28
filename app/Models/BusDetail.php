<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusDetail extends Model
{
    protected $table      = 'bus_detail_tables';
    public    $timestamps = true;
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
        'driver_id',
        // Phase 3 — delay reporting
        'delay_minutes',
        'delay_reason',
        'delay_reported_at',
    ];

    protected $casts = [
        'latitude'          => 'float',
        'longitude'         => 'float',
        'delay_minutes'     => 'integer',
        'delay_reported_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'driver_id');
    }

    // Helper: is this bus currently delayed?
    public function isDelayed(): bool
    {
        return $this->delay_minutes !== null && $this->delay_minutes > 0;
    }
}
