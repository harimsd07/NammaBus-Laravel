<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusDetail extends Model
{
    // Logic: Explicitly link to the exact table name from your migration
    protected $table = 'bus_detail_tables';

    // Logic: Disable timestamps since your raw DB check showed they are empty
    
    // Logic: Ensure the primary key is set to 'id' (standard, but safe to define)
    protected $primaryKey = 'id';

    protected $fillable = [
    'busNameOrbusNo',  // ← was 'local' — WRONG!
    'vehicle_no',
    'pick_up_stop',
    'destination',
    'pickup_time',
    'reach_destination_time',
    'latitude',
    'longitude',
];
}
