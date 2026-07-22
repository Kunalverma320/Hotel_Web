<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            HotelSeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            EmployeeSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
