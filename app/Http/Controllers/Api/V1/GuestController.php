<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $guests = Guest::query()
            ->when($request->search, fn($q, $s) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"))
            ->latest()
            ->paginate(15);

        return response()->json($guests);
    }

    public function show(Guest $guest)
    {
        $guest->load(['bookings', 'documents', 'preferences']);
        return response()->json($guest);
    }

    public function update(Request $request, Guest $guest): JsonResponse
    {
        $guest->update($request->only([
            'first_name', 'last_name', 'email', 'phone', 'nationality',
            'date_of_birth', 'gender', 'address', 'city', 'country',
        ]));

        return response()->json(['message' => 'Profile updated', 'guest' => $guest]);
    }
}
