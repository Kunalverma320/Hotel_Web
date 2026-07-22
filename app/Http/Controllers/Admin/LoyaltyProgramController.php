<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;

class LoyaltyProgramController extends Controller
{
    public function index()
    {
        $programs = LoyaltyProgram::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.marketing.loyalty', compact('programs'));
    }

    public function create()
    {
        return view('admin.marketing.loyalty-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_per_dollar' => 'required|numeric|min:0',
            'redemption_rate' => 'required|numeric|min:0',
            'min_points' => 'nullable|integer|min:0',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = true;
        LoyaltyProgram::create($validated);
        return redirect()->route('admin.marketing.loyalty-programs.index')->with('success', 'Loyalty program created.');
    }

    public function edit(LoyaltyProgram $loyaltyProgram)
    {
        return view('admin.marketing.loyalty-form', ['program' => $loyaltyProgram]);
    }

    public function update(Request $request, LoyaltyProgram $loyaltyProgram)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_per_dollar' => 'required|numeric|min:0',
            'redemption_rate' => 'required|numeric|min:0',
            'min_points' => 'nullable|integer|min:0',
        ]);
        $loyaltyProgram->update($validated);
        return redirect()->route('admin.marketing.loyalty-programs.index')->with('success', 'Loyalty program updated.');
    }

    public function destroy(LoyaltyProgram $loyaltyProgram)
    {
        $loyaltyProgram->delete();
        return redirect()->route('admin.marketing.loyalty-programs.index')->with('success', 'Loyalty program deleted.');
    }
}
