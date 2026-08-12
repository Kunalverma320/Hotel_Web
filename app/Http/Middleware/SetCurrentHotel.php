<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentHotel
{
    public function handle(Request $request, Closure $next): Response
    {
        $hotelId = session('current_hotel_id');

        $hotel = $hotelId ? Hotel::find($hotelId) : null;

        if ($hotel) {
            view()->share('currentHotel', $hotel);
            config(['app.current_hotel_id' => $hotel->id]);
        } else {
            view()->share('currentHotel', null);
            config(['app.current_hotel_id' => null]);
        }

        $allHotels = Hotel::active()->orderBy('name')->get();
        if ($allHotels->isEmpty()) {
            $allHotels = Hotel::orderBy('name')->get();
        }

        view()->share('hotels', $allHotels);
        view()->share('allHotels', $allHotels);
        view()->share('currentUser', Auth::user());

        return $next($request);
    }
}
