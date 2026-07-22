<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Timezone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $currency = Currency::firstOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'is_default' => true,
            'status' => true,
        ]);

        Currency::firstOrCreate(['code' => 'EUR'], [
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => 0.920000,
            'is_default' => false,
            'status' => true,
        ]);

        Currency::firstOrCreate(['code' => 'INR'], [
            'name' => 'Indian Rupee',
            'symbol' => '₹',
            'exchange_rate' => 83.500000,
            'is_default' => false,
            'status' => true,
        ]);

        $timezone = Timezone::firstOrCreate(['name' => 'America/New_York'], [
            'offset' => -5,
            'status' => true,
        ]);

        Timezone::firstOrCreate(['name' => 'Asia/Kolkata'], [
            'offset' => 5.5,
            'status' => true,
        ]);

        Language::firstOrCreate(['code' => 'en'], [
            'name' => 'English',
            'native_name' => 'English',
            'direction' => 'ltr',
            'is_default' => true,
            'status' => true,
        ]);

        $company = Company::firstOrCreate(
            ['email' => 'info@luxuryhotels.com'],
            [
                'name' => 'Luxury Hotels International',
                'slug' => Str::slug('Luxury Hotels International'),
                'phone' => '+1-555-0100',
                'website' => 'https://luxuryhotels.com',
                'address' => '100 Luxury Avenue, New York, NY 10001',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'zipcode' => '10001',
                'currency_id' => $currency->id,
                'timezone_id' => $timezone->id,
                'gst_number' => 'GST-98765-001',
                'pan_number' => 'PAN-ABC123456',
                'status' => true,
            ]
        );

        $branch = Branch::firstOrCreate(
            ['email' => 'nyc@luxuryhotels.com'],
            [
                'company_id' => $company->id,
                'name' => 'New York City Branch',
                'slug' => Str::slug('New York City Branch'),
                'phone' => '+1-555-0101',
                'address' => '200 Broadway',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'zipcode' => '10007',
                'status' => true,
            ]
        );

        Branch::firstOrCreate(
            ['email' => 'la@luxuryhotels.com'],
            [
                'company_id' => $company->id,
                'name' => 'Los Angeles Branch',
                'slug' => Str::slug('Los Angeles Branch'),
                'phone' => '+1-555-0201',
                'address' => '300 Hollywood Blvd',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'zipcode' => '90001',
                'status' => true,
            ]
        );
    }
}
