<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john.smith@email.com', 'phone' => '+1-212-555-1001', 'nationality' => 'US', 'city' => 'New York', 'country' => 'USA', 'gender' => 'male', 'loyalty_points' => 12500],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.j@email.com', 'phone' => '+1-310-555-2002', 'nationality' => 'US', 'city' => 'Los Angeles', 'country' => 'USA', 'gender' => 'female', 'loyalty_points' => 4500],
            ['first_name' => 'Michael', 'last_name' => 'Williams', 'email' => 'michael.w@email.com', 'phone' => '+44-20-5555-3003', 'nationality' => 'GB', 'city' => 'London', 'country' => 'UK', 'gender' => 'male', 'loyalty_points' => 1200],
            ['first_name' => 'Emma', 'last_name' => 'Brown', 'email' => 'emma.brown@email.com', 'phone' => '+1-305-555-4004', 'nationality' => 'US', 'city' => 'Miami', 'country' => 'USA', 'gender' => 'female', 'loyalty_points' => 3200],
            ['first_name' => 'James', 'last_name' => 'Davis', 'email' => 'james.davis@email.com', 'phone' => '+1-303-555-5005', 'nationality' => 'US', 'city' => 'Denver', 'country' => 'USA', 'gender' => 'male', 'loyalty_points' => 800],
            ['first_name' => 'Maria', 'last_name' => 'Garcia', 'email' => 'maria.garcia@email.com', 'phone' => '+52-55-5555-6006', 'nationality' => 'MX', 'city' => 'Mexico City', 'country' => 'Mexico', 'gender' => 'female', 'loyalty_points' => 1800],
            ['first_name' => 'Robert', 'last_name' => 'Martinez', 'email' => 'robert.m@email.com', 'phone' => '+1-702-555-7007', 'nationality' => 'US', 'city' => 'Las Vegas', 'country' => 'USA', 'gender' => 'male', 'loyalty_points' => 200],
            ['first_name' => 'Jennifer', 'last_name' => 'Anderson', 'email' => 'jennifer.a@email.com', 'phone' => '+1-617-555-8008', 'nationality' => 'US', 'city' => 'Boston', 'country' => 'USA', 'gender' => 'female', 'loyalty_points' => 8200],
            ['first_name' => 'David', 'last_name' => 'Taylor', 'email' => 'david.taylor@email.com', 'phone' => '+61-2-5555-9009', 'nationality' => 'AU', 'city' => 'Sydney', 'country' => 'Australia', 'gender' => 'male', 'loyalty_points' => 3800],
            ['first_name' => 'Sophie', 'last_name' => 'Martin', 'email' => 'sophie.martin@email.com', 'phone' => '+33-1-5555-1010', 'nationality' => 'FR', 'city' => 'Paris', 'country' => 'France', 'gender' => 'female', 'loyalty_points' => 1500],
            ['first_name' => 'Daniel', 'last_name' => 'Lee', 'email' => 'daniel.lee@email.com', 'phone' => '+82-2-5555-1111', 'nationality' => 'KR', 'city' => 'Seoul', 'country' => 'South Korea', 'gender' => 'male', 'loyalty_points' => 600],
            ['first_name' => 'Olivia', 'last_name' => 'White', 'email' => 'olivia.white@email.com', 'phone' => '+1-312-555-1212', 'nationality' => 'US', 'city' => 'Chicago', 'country' => 'USA', 'gender' => 'female', 'loyalty_points' => 100],
            ['first_name' => 'William', 'last_name' => 'Harris', 'email' => 'william.h@email.com', 'phone' => '+1-415-555-1313', 'nationality' => 'US', 'city' => 'San Francisco', 'country' => 'USA', 'gender' => 'male', 'loyalty_points' => 2800],
            ['first_name' => 'Aiko', 'last_name' => 'Tanaka', 'email' => 'aiko.tanaka@email.com', 'phone' => '+81-3-5555-1414', 'nationality' => 'JP', 'city' => 'Tokyo', 'country' => 'Japan', 'gender' => 'female', 'loyalty_points' => 2100],
            ['first_name' => 'Mohammed', 'last_name' => 'Ali', 'email' => 'mohammed.ali@email.com', 'phone' => '+971-4-555-1515', 'nationality' => 'AE', 'city' => 'Dubai', 'country' => 'UAE', 'gender' => 'male', 'loyalty_points' => 9500],
            ['first_name' => 'Emily', 'last_name' => 'Clark', 'email' => 'emily.clark@email.com', 'phone' => '+1-206-555-1616', 'nationality' => 'US', 'city' => 'Seattle', 'country' => 'USA', 'gender' => 'female', 'loyalty_points' => 750],
            ['first_name' => 'Carlos', 'last_name' => 'Silva', 'email' => 'carlos.silva@email.com', 'phone' => '+55-11-5555-1717', 'nationality' => 'BR', 'city' => 'Sao Paulo', 'country' => 'Brazil', 'gender' => 'male', 'loyalty_points' => 3400],
            ['first_name' => 'Priya', 'last_name' => 'Patel', 'email' => 'priya.patel@email.com', 'phone' => '+91-22-5555-1818', 'nationality' => 'IN', 'city' => 'Mumbai', 'country' => 'India', 'gender' => 'female', 'loyalty_points' => 1600],
            ['first_name' => 'Thomas', 'last_name' => 'Mueller', 'email' => 'thomas.m@email.com', 'phone' => '+49-30-5555-1919', 'nationality' => 'DE', 'city' => 'Berlin', 'country' => 'Germany', 'gender' => 'male', 'loyalty_points' => 350],
            ['first_name' => 'Lisa', 'last_name' => 'Wong', 'email' => 'lisa.wong@email.com', 'phone' => '+65-5555-2020', 'nationality' => 'SG', 'city' => 'Singapore', 'country' => 'Singapore', 'gender' => 'female', 'loyalty_points' => 4200],
        ];

        foreach ($guests as $guestData) {
            Guest::firstOrCreate(
                ['email' => $guestData['email']],
                array_merge($guestData, [
                    'date_of_birth' => fake()->date('Y-m-d', '-25 years'),
                    'address' => fake()->address(),
                ])
            );
        }
    }
}
