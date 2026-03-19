<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusDetail;
use Carbon\Carbon;

class BusDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Logic: Defining common stops to make search testing easier
        $stops = ['Thiruvarur Junction', 'Chennai Central', 'Madurai', 'Trichy', 'Anna Nagar', 'Central Bus Stand'];

        for ($i = 0; $i < 15; $i++) {
            $from = $stops[array_rand($stops)];
            
            // Logic: Ensure destination is different from the pickup point
            do {
                $to = $stops[array_rand($stops)];
            } while ($from === $to);

            // Logic: Generates a random time within the next 12 hours for testing
            $randomTime = Carbon::now()->addHours(rand(1, 12))->addMinutes(rand(0, 59));

            BusDetail::create([
                'busNameOrbusNo' => 'Express ' . (100 + $i),
                'vehicle_no' => 'TN-' . rand(10, 99) . '-AQ-' . rand(1000, 9999),
                'pick_up_stop' => $from,
                'destination' => $to,
                'pickup_time' => $randomTime->format('H:i:s'),
                'reach_destination_time' => $randomTime->addHours(2)->format('H:i:s'),
                'latitude' => 10.7870, // Logic: Defaulting to a coordinate near Thiruvarur
                'longitude' => 79.6410,
            ]);
        }
    }
}
