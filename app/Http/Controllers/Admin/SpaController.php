<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function packages()
    {
        $packages = SpaPackage::with('services')->latest()->paginate(20);
        return view('admin.spa.packages', compact('packages'));
    }

    public function packageCreate()
    {
        return view('admin.spa.packages');
    }

    public function packageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        SpaPackage::create($request->only('name', 'description', 'price', 'duration_minutes', 'is_active'));

        return redirect()->route('admin.spa.packages')->with('success', 'Package created.');
    }

    public function packageEdit($id)
    {
        $package = SpaPackage::findOrFail($id);
        $packages = SpaPackage::with('services')->latest()->paginate(20);
        return view('admin.spa.packages', compact('packages', 'package'));
    }

    public function packageUpdate($id, Request $request)
    {
        $package = SpaPackage::findOrFail($id);
        $package->update($request->only('name', 'description', 'price', 'duration_minutes', 'is_active'));

        return redirect()->route('admin.spa.packages')->with('success', 'Package updated.');
    }

    public function packageDestroy($id)
    {
        SpaPackage::findOrFail($id)->delete();
        return redirect()->route('admin.spa.packages')->with('success', 'Package deleted.');
    }

    public function appointments()
    {
        $appointments = SpaAppointment::with('package', 'therapist', 'guest')
            ->latest()
            ->paginate(20);

        return view('admin.spa.appointments', compact('appointments'));
    }

    public function appointmentCreate()
    {
        $packages = SpaPackage::where('is_active', true)->get();
        $therapists = Staff::where('department', 'spa')->get();
        $guests = Guest::all();

        return view('admin.spa.appointments', compact('packages', 'therapists', 'guests'));
    }

    public function appointmentStore(Request $request)
    {
        $request->validate([
            'spa_package_id' => 'required|exists:spa_packages,id',
            'therapist_id' => 'required|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        SpaAppointment::create($request->only(
            'spa_package_id', 'therapist_id', 'guest_id',
            'appointment_date', 'appointment_time', 'notes'
        ));

        return redirect()->route('admin.spa.appointments')->with('success', 'Appointment booked.');
    }

    public function updateAppointmentStatus($id, $status)
    {
        $appointment = SpaAppointment::findOrFail($id);
        $appointment->update(['status' => $status]);

        return redirect()->back()->with('success', 'Appointment status updated.');
    }

    public function therapists()
    {
        $therapists = Staff::where('department', 'spa')->with('spaAppointments')->get();
        return view('admin.spa.therapists', compact('therapists'));
    }
}
