<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = GymMembership::where('hotel_id', session('current_hotel_id'))->with('guest')->latest()->paginate(20);
        return view('admin.gym.memberships', compact('memberships'));
    }

    public function create() { return view('admin.gym.memberships'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'membership_type' => 'required|in:daily,weekly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount' => 'required|numeric|min:0',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = 'active';
        GymMembership::create($validated);
        return redirect()->route('admin.gym.memberships.index')->with('success', 'Membership created.');
    }

    public function edit(GymMembership $membership)
    {
        return view('admin.gym.memberships', compact('membership'));
    }

    public function update(Request $request, GymMembership $membership)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,expired,cancelled',
        ]);
        $membership->update($validated);
        return redirect()->route('admin.gym.memberships.index')->with('success', 'Membership updated.');
    }

    public function destroy(GymMembership $membership)
    {
        $membership->delete();
        return redirect()->route('admin.gym.memberships.index')->with('success', 'Membership deleted.');
    }
}
