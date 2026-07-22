<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymEquipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = GymEquipment::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.gym.equipment', compact('equipment'));
    }

    public function create() { return view('admin.gym.equipment'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = 'available';
        GymEquipment::create($validated);
        return redirect()->route('admin.gym.equipment.index')->with('success', 'Equipment added.');
    }

    public function edit(GymEquipment $equipment)
    {
        return view('admin.gym.equipment', compact('equipment'));
    }

    public function update(Request $request, GymEquipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
        ]);
        $equipment->update($validated);
        return redirect()->route('admin.gym.equipment.index')->with('success', 'Equipment updated.');
    }

    public function destroy(GymEquipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('admin.gym.equipment.index')->with('success', 'Equipment deleted.');
    }
}
