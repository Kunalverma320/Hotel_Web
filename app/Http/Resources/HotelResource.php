<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'logo' => $this->logo,
            'cover_image' => $this->cover_image,
            'star_rating' => $this->star_rating,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'images' => HotelImageResource::collection($this->whenLoaded('hotelImages')),
            'amenities' => $this->whenLoaded('amenities'),
            'rules' => $this->whenLoaded('hotelRules'),
            'room_types' => RoomTypeResource::collection($this->whenLoaded('roomTypes')),
        ];
    }
}
