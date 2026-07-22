<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpaPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = SpaPackage::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.spa.packages', compact('packages'));
    }

    public function create() { return view('admin.spa.packages'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = true;
        SpaPackage::create($validated);
        return redirect()->route('admin.spa.packages.index')->with('success', 'Package created.');
    }

    public function edit(SpaPackage $package)
    {
        return view('admin.spa.packages', compact('package'));
    }

    public function update(Request $request, SpaPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);
        $package->update($validated);
        return redirect()->route('admin.spa.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(SpaPackage $package)
    {
        $package->delete();
        return redirect()->route('admin.spa.packages.index')->with('success', 'Package deleted.');
    }
}
