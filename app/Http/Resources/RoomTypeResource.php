<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'base_rate' => $this->base_rate,
            'max_adults' => $this->max_adults,
            'max_children' => $this->max_children,
            'max_occupancy' => $this->max_occupancy,
            'bed_type' => $this->bed_type,
            'room_size' => $this->room_size,
            'smoking' => $this->smoking,
            'balcony' => $this->balcony,
            'sea_view' => $this->sea_view,
            'image' => $this->image,
            'available_rooms' => $this->whenCounted('rooms'),
        ];
    }
}
