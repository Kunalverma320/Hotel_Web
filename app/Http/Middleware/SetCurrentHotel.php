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
        $hotelId = session('current_hotel_id')
            ?? $request->input('hotel_id')
            ?? $request->header('X-Hotel-Id');

        if ($hotelId) {
            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                view()->share('currentHotel', $hotel);
                config(['app.current_hotel_id' => $hotelId]);
                $request->merge(['hotel_id' => $hotelId]);
            }
        }

        view()->share('hotels', Hotel::orderBy('name')->get());
        view()->share('currentUser', Auth::user());

        return $next($request);
    }
}
