<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$customers = DB::table('guests')
    ->select(
        'guests.id',
        'guests.email',
        DB::raw("TRIM(CONCAT(guests.first_name, ' ', COALESCE(guests.last_name, ''))) as name"),
        DB::raw('COUNT(bookings.id) as bookings_count'),
        DB::raw('COALESCE(SUM(bookings.total_amount), 0) as total_spent')
    )
    ->leftJoin('bookings', 'guests.id', '=', 'bookings.guest_id')
    ->groupBy('guests.id', 'guests.first_name', 'guests.last_name', 'guests.email')
    ->orderByDesc('total_spent')
    ->limit(5)
    ->get();

echo "Top Customers Count: " . count($customers) . "\n";
print_r($customers);

$hotels = DB::table('hotels')
    ->select(
        'hotels.id',
        'hotels.name',
        DB::raw('COUNT(DISTINCT bookings.id) as bookings_count'),
        DB::raw('COALESCE(SUM(payments.amount), 0) as revenue')
    )
    ->leftJoin('bookings', 'hotels.id', '=', 'bookings.hotel_id')
    ->leftJoin('payments', 'bookings.id', '=', 'payments.booking_id')
    ->groupBy('hotels.id', 'hotels.name')
    ->orderByDesc('revenue')
    ->limit(5)
    ->get();

echo "Top Hotels Count: " . count($hotels) . "\n";

$rooms = DB::table('rooms')
    ->select(
        'rooms.id',
        'rooms.room_number',
        'room_types.name as room_type_name',
        DB::raw('COUNT(DISTINCT booking_rooms.booking_id) as bookings_count'),
        DB::raw('COALESCE(SUM(bookings.total_amount), 0) as revenue')
    )
    ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    ->leftJoin('booking_rooms', 'rooms.id', '=', 'booking_rooms.room_id')
    ->leftJoin('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
    ->groupBy('rooms.id', 'rooms.room_number', 'room_types.name')
    ->orderByDesc('bookings_count')
    ->limit(5)
    ->get();

echo "Top Rooms Count: " . count($rooms) . "\n";
echo "SUCCESS!\n";
