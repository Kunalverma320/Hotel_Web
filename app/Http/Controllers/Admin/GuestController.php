<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestDocument;
use App\Models\GuestPreference;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::withCount('bookings');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }
        if ($request->filled('is_blacklisted')) {
            $query->where('is_blacklisted', $request->boolean('is_blacklisted'));
        }
        if ($request->filled('loyalty_tier')) {
            $query->where('loyalty_tier', $request->loyalty_tier);
        }
        if ($request->filled('has_bookings')) {
            $query->has('bookings');
        }

        $guests = $query->latest()->paginate(20);
        $nationalities = Guest::distinct()->whereNotNull('nationality')->pluck('nationality')->sort()->values();

        return view('admin.guests.index', compact('guests', 'nationalities'));
    }

    public function create()
    {
        return view('admin.guests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'secondary_phone' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        Guest::create($request->only([
            'first_name', 'last_name', 'email', 'phone', 'secondary_phone',
            'date_of_birth', 'gender', 'nationality', 'id_type', 'id_number',
            'company_name', 'address', 'city', 'state', 'country', 'postal_code', 'notes',
        ]));

        return redirect()->route('admin.guests.index')->with('success', 'Guest created successfully.');
    }

    public function show($id)
    {
        $guest = Guest::with([
            'bookings' => function ($q) { $q->latest()->limit(10); },
            'documents',
            'preferences',
            'loyaltyTransactions' => function ($q) { $q->latest()->limit(20); },
            'customerNotes' => function ($q) { $q->latest()->limit(10); },
        ])->findOrFail($id);

        $totalSpent = $guest->bookings()->sum('total_amount');
        $totalStays = $guest->bookings()->whereIn('status', ['checked_out'])->count();

        return view('admin.guests.show', compact('guest', 'totalSpent', 'totalStays'));
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);

        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'secondary_phone' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $guest->update($request->only([
            'first_name', 'last_name', 'email', 'phone', 'secondary_phone',
            'date_of_birth', 'gender', 'nationality', 'id_type', 'id_number',
            'company_name', 'address', 'city', 'state', 'country', 'postal_code', 'notes',
        ]));

        return redirect()->route('admin.guests.show', $guest->id)->with('success', 'Guest updated successfully.');
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();

        return redirect()->route('admin.guests.index')->with('success', 'Guest deleted successfully.');
    }

    public function documents($id)
    {
        $guest = Guest::with('documents')->findOrFail($id);

        return view('admin.guests.documents', compact('guest'));
    }

    public function preferences($id)
    {
        $guest = Guest::with('preferences')->findOrFail($id);

        return view('admin.guests.preferences', compact('guest'));
    }

    public function history($id)
    {
        $guest = Guest::with(['bookings.roomType', 'bookings.bookingRooms.room'])->findOrFail($id);
        $bookings = $guest->bookings()->latest()->paginate(15);

        return view('admin.guests.history', compact('guest', 'bookings'));
    }

    public function blacklist($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->update([
            'is_blacklisted' => !$guest->is_blacklisted,
            'blacklist_reason' => $guest->is_blacklisted ? null : request('reason', 'No reason provided'),
        ]);

        $status = $guest->is_blacklisted ? 'blacklisted' : 'removed from blacklist';
        return redirect()->back()->with('success', "Guest {$status} successfully.");
    }

    public function loyalty($id)
    {
        $guest = Guest::with(['loyaltyTransactions.loyaltyProgram', 'loyaltyTransactions.booking'])->findOrFail($id);

        return view('admin.guests.loyalty', compact('guest'));
    }
}
