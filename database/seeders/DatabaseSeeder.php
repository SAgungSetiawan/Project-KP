<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'atmin 005',
            'email' => 'admin@orrea.com',
            'password' => Hash::make('password'),
            
        ]);

        // Create dummy clients for 2024
        $months2024 = [
            1 => 10, 2 => 12, 3 => 13, 4 => 8, 5 => 11, 6 => 10,
            7 => 12, 8 => 11, 9 => 10, 10 => 11, 11 => 12, 12 => 11
        ];
        
        $id = 1;
        foreach ($months2024 as $month => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $day = rand(1, 28);
                Client::create([
                    'name' => 'Client ' . $id++,
                    'nama_brand' => 'Brand' . $id++,
                    'phone' => '0812' . rand(100000, 999999),
                    'email' => 'client' . $id . '@example.com',
                    'address' => 'Jl. Contoh No.' . $id . ', Jakarta',
                    'category' => ['Basic', 'Growth', 'Business', 'Premium'][rand(0, 3)],
                    'join_date' => Carbon::create(2024, $month, $day),
                    'status' => rand(0, 1) ? 'active' : 'non active',
                    'notes' => 'Client contoh untuk testing sistem'
                ]);
            }
        }

        // Create dummy clients for 2023
        $months2023 = [
            1 => 8, 2 => 9, 3 => 10, 4 => 7, 5 => 9, 6 => 8,
            7 => 10, 8 => 9, 9 => 8, 10 => 9, 11 => 10, 12 => 9
        ];
        
        foreach ($months2023 as $month => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $day = rand(1, 28);
                Client::create([
                    'name' => 'Client ' . $id++,
                    'nama_brand' => 'Brand' . $id++,
                    'phone' => '0813' . rand(100000, 999999),
                    'email' => 'client' . $id . '@example.com',
                    'address' => 'Jl. Testing No.' . $id . ', Bandung',
                    'category' => ['Basic', 'Growth', 'Business', 'Premium'][rand(0, 3)],
                    'join_date' => Carbon::create(2023, $month, $day),
                    'status' => 'active',
                    'notes' => 'Client lama dari tahun 2023'
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Total clients created: ' . ($id - 1));
        $this->command->info('Admin Login: admin@orrea.com / password');
    }
}