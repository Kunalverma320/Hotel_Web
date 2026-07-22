<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'adults' => $this->adults,
            'children' => $this->children,
            'total_nights' => $this->total_nights,
            'room_rate' => $this->room_rate,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
            'guest' => $this->whenLoaded('guest'),
            'created_at' => $this->created_at,
        ];
    }
}
