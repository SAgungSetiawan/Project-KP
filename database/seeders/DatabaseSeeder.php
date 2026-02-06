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
        // Cek apakah admin user sudah ada
        if (!User::where('email', env('ADMIN_EMAIL', 'admin@rocker.com'))->exists()) {
            // Create admin user dari environment variables
            User::create([
                'name' => env('ADMIN_NAME', 'Admin Rocker'),
                'email' => env('ADMIN_EMAIL', 'admin@rocker.com'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]);
        }
    }
}