<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function equipment()
    {
        $equipment = GymEquipment::latest()->paginate(20);
        return view('admin.gym.equipment', compact('equipment'));
    }

    public function equipmentCreate()
    {
        return view('admin.gym.equipment');
    }

    public function equipmentStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'status' => 'nullable|in:available,maintenance,retired',
        ]);

        GymEquipment::create($request->only('name', 'category', 'description', 'quantity', 'status'));

        return redirect()->route('admin.gym.equipment')->with('success', 'Equipment added.');
    }

    public function equipmentEdit($id)
    {
        $item = GymEquipment::findOrFail($id);
        $equipment = GymEquipment::latest()->paginate(20);
        return view('admin.gym.equipment', compact('equipment', 'item'));
    }

    public function equipmentUpdate($id, Request $request)
    {
        $item = GymEquipment::findOrFail($id);
        $item->update($request->only('name', 'category', 'description', 'quantity', 'status'));

        return redirect()->route('admin.gym.equipment')->with('success', 'Equipment updated.');
    }

    public function equipmentDestroy($id)
    {
        GymEquipment::findOrFail($id)->delete();
        return redirect()->route('admin.gym.equipment')->with('success', 'Equipment deleted.');
    }

    public function memberships()
    {
        $memberships = GymMembership::with('guest')->latest()->paginate(20);
        return view('admin.gym.memberships', compact('memberships'));
    }

    public function membershipCreate()
    {
        $guests = Guest::all();
        return view('admin.gym.memberships', compact('guests'));
    }

    public function membershipStore(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'type' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount' => 'required|numeric|min:0',
        ]);

        GymMembership::create($request->only('guest_id', 'type', 'start_date', 'end_date', 'amount'));

        return redirect()->route('admin.gym.memberships')->with('success', 'Membership created.');
    }

    public function schedules()
    {
        $schedules = GymSchedule::latest()->paginate(20);
        return view('admin.pool.schedules', compact('schedules'));
    }
}
