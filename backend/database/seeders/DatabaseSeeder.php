<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TravelSchedule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Travel',
            'email' => 'admin@travel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890'
        ]);

        // Create Passenger Users
        User::create([
            'name' => 'Nabil CF',
            'email' => 'passenger@travel.com',
            'password' => Hash::make('password'),
            'role' => 'passenger',
            'phone' => '081234567891'
        ]);

        User::create([
            'name' => 'Jono',
            'email' => 'jono@example.com',
            'password' => Hash::make('password'),
            'role' => 'passenger',
            'phone' => '081234567892'
        ]);

        // Create Travel Schedules
        $schedules = [
            [
                'destination' => 'Jakarta - Bandung',
                'departure_date' => now()->addDays(1)->format('Y-m-d'),
                'departure_time' => '08:00:00',
                'quota' => 20,
                'available_quota' => 20,
                'price' => 150000
            ],
            [
                'destination' => 'Jakarta - Bandung',
                'departure_date' => now()->addDays(1)->format('Y-m-d'),
                'departure_time' => '14:00:00',
                'quota' => 20,
                'available_quota' => 15,
                'price' => 150000
            ],
            [
                'destination' => 'Jakarta - Surabaya',
                'departure_date' => now()->addDays(2)->format('Y-m-d'),
                'departure_time' => '09:00:00',
                'quota' => 30,
                'available_quota' => 30,
                'price' => 300000
            ],
            [
                'destination' => 'Jakarta - Yogyakarta',
                'departure_date' => now()->addDays(2)->format('Y-m-d'),
                'departure_time' => '10:00:00',
                'quota' => 25,
                'available_quota' => 20,
                'price' => 200000
            ],
            [
                'destination' => 'Bandung - Semarang',
                'departure_date' => now()->addDays(3)->format('Y-m-d'),
                'departure_time' => '07:00:00',
                'quota' => 18,
                'available_quota' => 18,
                'price' => 180000
            ],
            [
                'destination' => 'Jakarta - Bali',
                'departure_date' => now()->addDays(5)->format('Y-m-d'),
                'departure_time' => '06:00:00',
                'quota' => 35,
                'available_quota' => 30,
                'price' => 500000
            ],
        ];

        foreach ($schedules as $schedule) {
            TravelSchedule::create($schedule);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@travel.com / password');
        $this->command->info('Passenger: passenger@travel.com / password');
    }
}
