<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotelms.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $admin->assignRole('super-admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@hotelms.com'],
            [
                'name' => 'Hotel Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $manager->assignRole('manager');

        $receptionist = User::firstOrCreate(
            ['email' => 'reception@hotelms.com'],
            [
                'name' => 'Receptionist',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $receptionist->assignRole('receptionist');

        $user = User::firstOrCreate(
            ['email' => 'user@hotelms.com'],
            [
                'name' => 'Guest User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => true,
            ]
        );
        $user->assignRole('front-desk');
    }
}
