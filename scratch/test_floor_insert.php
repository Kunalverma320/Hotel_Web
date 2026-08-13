<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Floor;

$floor = Floor::create([
    'hotel_id' => 1,
    'building_id' => 1,
    'name' => 'Floor Test 99',
    'floor_number' => 99,
    'description' => 'Test description for floor 99',
    'status' => true,
]);

echo "Floor created successfully with ID: " . $floor->id . "\n";
$floor->delete();
echo "Floor cleaned up!\n";
