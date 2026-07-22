<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $company = \App\Models\Company::first();
        if (!$company) return;

        $branch = \App\Models\Branch::where('company_id', $company->id)->first();

        $amenities = [
            ['name' => 'Free WiFi', 'icon' => 'wifi', 'category' => 'connectivity', 'status' => true],
            ['name' => 'Swimming Pool', 'icon' => 'pool', 'category' => 'recreation', 'status' => true],
            ['name' => 'Fitness Center', 'icon' => 'fitness', 'category' => 'recreation', 'status' => true],
            ['name' => 'Restaurant', 'icon' => 'restaurant', 'category' => 'dining', 'status' => true],
            ['name' => 'Room Service', 'icon' => 'room-service', 'category' => 'dining', 'status' => true],
            ['name' => 'Parking', 'icon' => 'parking', 'category' => 'convenience', 'status' => true],
            ['name' => 'Airport Shuttle', 'icon' => 'shuttle', 'category' => 'transport', 'status' => true],
            ['name' => 'Spa', 'icon' => 'spa', 'category' => 'wellness', 'status' => true],
            ['name' => 'Business Center', 'icon' => 'business', 'category' => 'business', 'status' => true],
            ['name' => 'Laundry', 'icon' => 'laundry', 'category' => 'services', 'status' => true],
            ['name' => 'Air Conditioning', 'icon' => 'ac', 'category' => 'room', 'status' => true],
            ['name' => 'Mini Bar', 'icon' => 'minibar', 'category' => 'room', 'status' => true],
            ['name' => 'Flat Screen TV', 'icon' => 'tv', 'category' => 'room', 'status' => true],
            ['name' => 'In-Room Safe', 'icon' => 'safe', 'category' => 'room', 'status' => true],
            ['name' => 'Coffee Maker', 'icon' => 'coffee', 'category' => 'room', 'status' => true],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['name' => $amenity['name']], $amenity);
        }
        $allAmenities = Amenity::all();

        $categories = [
            ['name' => 'Standard', 'slug' => 'standard', 'description' => 'Standard rooms with essential amenities', 'status' => true, 'sort_order' => 1],
            ['name' => 'Deluxe', 'slug' => 'deluxe', 'description' => 'Deluxe rooms with upgraded amenities', 'status' => true, 'sort_order' => 2],
            ['name' => 'Suite', 'slug' => 'suite', 'description' => 'Spacious suites with premium amenities', 'status' => true, 'sort_order' => 3],
            ['name' => 'Penthouse', 'slug' => 'penthouse', 'description' => 'Top-floor luxury penthouses', 'status' => true, 'sort_order' => 4],
        ];

        $hotelsData = [
            [
                'name' => 'Grand Luxury Hotel NYC',
                'slug' => 'grand-luxury-hotel-nyc',
                'tagline' => 'Experience luxury in the heart of Manhattan',
                'description' => 'A premier luxury hotel in the heart of New York City, offering world-class amenities and exceptional service.',
                'star_rating' => 5,
                'email' => 'reservations@grandluxurynyc.com',
                'phone' => '+1-212-555-0100',
                'website' => 'https://grandluxurynyc.com',
                'address' => '400 Park Avenue',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'zipcode' => '10022',
                'latitude' => '40.7614',
                'longitude' => '-73.9735',
                'check_in_time' => '15:00',
                'check_out_time' => '11:00',
                'status' => true,
                'room_types' => [
                    ['name' => 'Standard Room', 'slug' => 'standard-room', 'base_rate' => 199.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'queen', 'count' => 20],
                    ['name' => 'Deluxe Room', 'slug' => 'deluxe-room', 'base_rate' => 299.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'king', 'count' => 15],
                    ['name' => 'Junior Suite', 'slug' => 'junior-suite', 'base_rate' => 449.00, 'max_adults' => 3, 'max_children' => 1, 'max_occupancy' => 4, 'bed_type' => 'king', 'count' => 10],
                    ['name' => 'Executive Suite', 'slug' => 'executive-suite', 'base_rate' => 599.00, 'max_adults' => 4, 'max_children' => 2, 'max_occupancy' => 6, 'bed_type' => 'king', 'count' => 5],
                    ['name' => 'Penthouse Suite', 'slug' => 'penthouse-suite', 'base_rate' => 1299.00, 'max_adults' => 6, 'max_children' => 2, 'max_occupancy' => 8, 'bed_type' => 'king', 'count' => 2, 'balcony' => true, 'sea_view' => true],
                ],
            ],
            [
                'name' => 'Seaside Resort Miami',
                'slug' => 'seaside-resort-miami',
                'tagline' => 'Where the ocean meets luxury',
                'description' => 'Beautiful beachfront resort in Miami with stunning ocean views and tropical surroundings.',
                'star_rating' => 4,
                'email' => 'info@seasidemiami.com',
                'phone' => '+1-305-555-0200',
                'website' => 'https://seasidemiami.com',
                'address' => '500 Ocean Drive',
                'city' => 'Miami Beach',
                'state' => 'FL',
                'country' => 'USA',
                'zipcode' => '33139',
                'latitude' => '25.7617',
                'longitude' => '-80.1918',
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'status' => true,
                'room_types' => [
                    ['name' => 'Garden View', 'slug' => 'garden-view', 'base_rate' => 159.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'queen', 'garden_view' => true, 'count' => 20],
                    ['name' => 'Ocean View', 'slug' => 'ocean-view', 'base_rate' => 229.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'king', 'sea_view' => true, 'count' => 15],
                    ['name' => 'Beach Suite', 'slug' => 'beach-suite', 'base_rate' => 399.00, 'max_adults' => 4, 'max_children' => 2, 'max_occupancy' => 6, 'bed_type' => 'king', 'sea_view' => true, 'balcony' => true, 'count' => 8],
                    ['name' => 'Family Suite', 'slug' => 'family-suite', 'base_rate' => 499.00, 'max_adults' => 4, 'max_children' => 3, 'max_occupancy' => 7, 'bed_type' => 'queen', 'sea_view' => true, 'count' => 5],
                ],
            ],
            [
                'name' => 'Mountain Lodge Denver',
                'slug' => 'mountain-lodge-denver',
                'tagline' => 'Retreat to the Rockies',
                'description' => 'Cozy mountain lodge near Denver with stunning Rocky Mountain views and outdoor activities.',
                'star_rating' => 4,
                'email' => 'info@mountainlodge.com',
                'phone' => '+1-303-555-0300',
                'website' => 'https://mountainlodge.com',
                'address' => '700 Mountain Road',
                'city' => 'Denver',
                'state' => 'CO',
                'country' => 'USA',
                'zipcode' => '80202',
                'latitude' => '39.7392',
                'longitude' => '-104.9903',
                'check_in_time' => '15:00',
                'check_out_time' => '12:00',
                'status' => true,
                'room_types' => [
                    ['name' => 'Cabin Room', 'slug' => 'cabin-room', 'base_rate' => 139.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'queen', 'count' => 15],
                    ['name' => 'Mountain View', 'slug' => 'mountain-view-room', 'base_rate' => 199.00, 'max_adults' => 2, 'max_children' => 1, 'max_occupancy' => 3, 'bed_type' => 'king', 'mountain_view' => true, 'count' => 10],
                    ['name' => 'Lodge Suite', 'slug' => 'lodge-suite', 'base_rate' => 349.00, 'max_adults' => 4, 'max_children' => 2, 'max_occupancy' => 6, 'bed_type' => 'king', 'mountain_view' => true, 'balcony' => true, 'count' => 5],
                    ['name' => 'Chalet', 'slug' => 'chalet', 'base_rate' => 599.00, 'max_adults' => 6, 'max_children' => 3, 'max_occupancy' => 9, 'bed_type' => 'king', 'mountain_view' => true, 'balcony' => true, 'count' => 3],
                ],
            ],
        ];

        foreach ($hotelsData as $hotelData) {
            $roomTypesData = $hotelData['room_types'];
            unset($hotelData['room_types']);
            $hotelData['company_id'] = $company->id;
            if ($branch) $hotelData['branch_id'] = $branch->id;

            $hotel = Hotel::firstOrCreate(['slug' => $hotelData['slug']], $hotelData);

            $building = Building::firstOrCreate(
                ['hotel_id' => $hotel->id, 'name' => 'Main Building'],
                ['hotel_id' => $hotel->id, 'name' => 'Main Building', 'description' => 'Main hotel building', 'status' => true]
            );

            for ($f = 1; $f <= 5; $f++) {
                Floor::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'name' => "Floor $f"],
                    ['hotel_id' => $hotel->id, 'name' => "Floor $f", 'floor_number' => $f, 'building_id' => $building->id, 'status' => true]
                );
            }

            $hotel->amenities()->syncWithoutDetaching(
                $allAmenities->random(min(8, $allAmenities->count()))->pluck('id')->toArray()
            );

            $hotelCategories = [];
            foreach ($categories as $cat) {
                $catData = $cat;
                $catData['hotel_id'] = $hotel->id;
                $catData['slug'] = $hotel->slug . '-' . $cat['slug'];
                $hotelCategories[$cat['slug']] = RoomCategory::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'slug' => $catData['slug']],
                    $catData
                );
            }

            foreach ($roomTypesData as $rtData) {
                $count = $rtData['count'];
                unset($rtData['count']);
                $rtData['hotel_id'] = $hotel->id;
                $rtData['room_category_id'] = $hotelCategories['standard']->id;
                $rtData['status'] = true;

                $roomType = RoomType::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'slug' => $rtData['slug']],
                    $rtData
                );

                $floors = Floor::where('hotel_id', $hotel->id)->get();
                for ($i = 1; $i <= $count; $i++) {
                    $floor = $floors->get($i % $floors->count());
                    Room::firstOrCreate(
                        ['hotel_id' => $hotel->id, 'room_number' => str_pad($i, 3, '0', STR_PAD_LEFT)],
                        [
                            'room_type_id' => $roomType->id,
                            'building_id' => $building->id,
                            'floor_id' => $floor?->id,
                            'room_name' => $rtData['name'] . ' ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                            'status' => 'available',
                            'housekeeping_status' => 'clean',
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
