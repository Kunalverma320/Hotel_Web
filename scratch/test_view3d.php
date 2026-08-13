<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Floor;
use App\Models\Hotel;

$hotel = Hotel::first();
if ($hotel) {
    $floors = Floor::active()->when($hotel->id, fn($q) => $q->byHotel($hotel->id))->orderBy('floor_number')->get();
    echo "Hotel: " . $hotel->name . " (ID: " . $hotel->id . ")\n";
    echo "Floors count for this hotel: " . count($floors) . "\n";
    foreach ($floors as $f) {
        echo " - Floor ID: {$f->id}, Name: {$f->name}, Number: {$f->floor_number}, Hotel ID: {$f->hotel_id}\n";
    }
}
