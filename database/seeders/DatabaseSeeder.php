<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BusDetail;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Drivers ───────────────────────────────────────────────────────────
        $drivers = [
            ['name' => 'Murugan Selvam',    'email' => 'murugan@nammabus.com'],
            ['name' => 'Rajan Krishnan',    'email' => 'rajan@nammabus.com'],
            ['name' => 'Senthil Kumar',     'email' => 'senthil@nammabus.com'],
            ['name' => 'Arumugam Perumal',  'email' => 'arumugam@nammabus.com'],
            ['name' => 'Velu Pandian',      'email' => 'velu@nammabus.com'],
        ];

        $driverIds = [];
        foreach ($drivers as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name'     => $d['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'driver',
                ]
            );
            $driverIds[] = $user->id;
        }

        // ── Students ──────────────────────────────────────────────────────────
        $students = [
            ['name' => 'Priya Lakshmi',    'email' => 'priya@student.com'],
            ['name' => 'Arun Balaji',      'email' => 'arun@student.com'],
            ['name' => 'Deepa Shankar',    'email' => 'deepa@student.com'],
            ['name' => 'Karthik Raja',     'email' => 'karthik@student.com'],
            ['name' => 'Meena Durai',      'email' => 'meena@student.com'],
            ['name' => 'Vijay Anand',      'email' => 'vijay@student.com'],
            ['name' => 'Sudha Ravi',       'email' => 'sudha@student.com'],
            ['name' => 'Bala Suresh',      'email' => 'bala@student.com'],
        ];

        foreach ($students as $s) {
            User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name'     => $s['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'student',
                ]
            );
        }

        // ── Buses — Real Tiruchirappalli routes ───────────────────────────────
        $buses = [
            [
                'busNameOrbusNo'         => 'Route 1 — Trichy Express',
                'vehicle_no'             => 'TN 45 AB 1234',
                'pick_up_stop'           => 'Central Bus Stand',
                'destination'            => 'Srirangam',
                'pickup_time'            => '06:00:00',
                'reach_destination_time' => '07:00:00',
                'latitude'               => 10.8050,
                'longitude'              => 78.6856,
                'driver_id'              => $driverIds[0],
            ],
            [
                'busNameOrbusNo'         => 'Route 2 — Woraiyur Fast',
                'vehicle_no'             => 'TN 45 CD 5678',
                'pick_up_stop'           => 'Trichy Junction',
                'destination'            => 'Woraiyur',
                'pickup_time'            => '07:00:00',
                'reach_destination_time' => '07:45:00',
                'latitude'               => 10.7905,
                'longitude'              => 78.7047,
                'driver_id'              => $driverIds[1],
            ],
            [
                'busNameOrbusNo'         => 'Route 3 — Thillai Nagar Shuttle',
                'vehicle_no'             => 'TN 45 EF 9012',
                'pick_up_stop'           => 'Chathiram Bus Stand',
                'destination'            => 'Thillai Nagar',
                'pickup_time'            => '08:00:00',
                'reach_destination_time' => '08:30:00',
                'latitude'               => 10.8231,
                'longitude'              => 78.6930,
                'driver_id'              => $driverIds[2],
            ],
            [
                'busNameOrbusNo'         => 'Route 4 — Ariyamangalam Link',
                'vehicle_no'             => 'TN 45 GH 3456',
                'pick_up_stop'           => 'Central Bus Stand',
                'destination'            => 'Ariyamangalam',
                'pickup_time'            => '09:00:00',
                'reach_destination_time' => '09:45:00',
                'latitude'               => 10.8315,
                'longitude'              => 78.7198,
                'driver_id'              => $driverIds[3],
            ],
            [
                'busNameOrbusNo'         => 'Route 5 — Palakarai City',
                'vehicle_no'             => 'TN 45 IJ 7890',
                'pick_up_stop'           => 'Trichy Junction',
                'destination'            => 'Palakarai',
                'pickup_time'            => '10:00:00',
                'reach_destination_time' => '10:30:00',
                'latitude'               => 10.8150,
                'longitude'              => 78.6801,
                'driver_id'              => $driverIds[4],
            ],
            [
                'busNameOrbusNo'         => 'Route 6 — Mannarpuram Special',
                'vehicle_no'             => 'TN 45 KL 1122',
                'pick_up_stop'           => 'Chathiram Bus Stand',
                'destination'            => 'Mannarpuram',
                'pickup_time'            => '11:00:00',
                'reach_destination_time' => '11:45:00',
                'latitude'               => 10.8095,
                'longitude'              => 78.6920,
                'driver_id'              => null,
            ],
            [
                'busNameOrbusNo'         => 'Route 7 — KK Nagar Express',
                'vehicle_no'             => 'TN 45 MN 3344',
                'pick_up_stop'           => 'Central Bus Stand',
                'destination'            => 'KK Nagar',
                'pickup_time'            => '13:00:00',
                'reach_destination_time' => '13:40:00',
                'latitude'               => 10.8012,
                'longitude'              => 78.6734,
                'driver_id'              => null,
            ],
            [
                'busNameOrbusNo'         => 'Route 8 — Srirangam Pilgrim',
                'vehicle_no'             => 'TN 45 OP 5566',
                'pick_up_stop'           => 'Trichy Junction',
                'destination'            => 'Srirangam Temple',
                'pickup_time'            => '15:00:00',
                'reach_destination_time' => '15:50:00',
                'latitude'               => 10.8650,
                'longitude'              => 78.6920,
                'driver_id'              => null,
            ],
            [
                'busNameOrbusNo'         => 'Route 9 — NIT Trichy Campus',
                'vehicle_no'             => 'TN 45 QR 7788',
                'pick_up_stop'           => 'Central Bus Stand',
                'destination'            => 'NIT Trichy',
                'pickup_time'            => '07:30:00',
                'reach_destination_time' => '08:15:00',
                'latitude'               => 10.7600,
                'longitude'              => 78.8130,
                'driver_id'              => null,
            ],
            [
                'busNameOrbusNo'         => 'Route 10 — Rockfort Evening',
                'vehicle_no'             => 'TN 45 ST 9900',
                'pick_up_stop'           => 'Chathiram Bus Stand',
                'destination'            => 'Rockfort',
                'pickup_time'            => '17:00:00',
                'reach_destination_time' => '17:30:00',
                'latitude'               => 10.8202,
                'longitude'              => 78.6890,
                'driver_id'              => null,
            ],
        ];

        foreach ($buses as $bus) {
            BusDetail::firstOrCreate(
                ['vehicle_no' => $bus['vehicle_no']],
                $bus
            );
        }

        $this->command->info('✅ Seeded:');
        $this->command->info('   ' . count($drivers)  . ' drivers');
        $this->command->info('   ' . count($students) . ' students');
        $this->command->info('   ' . count($buses)    . ' buses (real Trichy routes)');
        $this->command->info('   Password for all users: password123');
    }
}
