<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwimmingPoolSchedule;
use Illuminate\Http\Request;

class PoolController extends Controller
{
    public function index()
    {
        $schedules = SwimmingPoolSchedule::where('hotel_id', session('current_hotel_id'))->get();
        return view('admin.pool.schedules', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'max_capacity' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        SwimmingPoolSchedule::create($validated);
        return redirect()->route('pool.schedules')->with('success', 'Schedule created.');
    }

    public function destroySchedule(SwimmingPoolSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('pool.schedules')->with('success', 'Schedule deleted.');
    }

    public function updateScheduleStatus(SwimmingPoolSchedule $schedule, Request $request)
    {
        $schedule->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
