<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('organizer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $venues = Venue::all();
        return view('admin.events.create', compact('venues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue_id' => 'required|exists:venues,id',
            'expected_guests' => 'required|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:planning,confirmed,cancelled,completed',
        ]);

        Event::create($request->only(
            'title', 'description', 'event_date', 'start_time', 'end_time',
            'venue_id', 'expected_guests', 'budget', 'status'
        ));

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function show($id)
    {
        $event = Event::with('venue', 'organizer')->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();
        return view('admin.events.create', compact('event', 'venues'));
    }

    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $event->update($request->only(
            'title', 'description', 'event_date', 'start_time', 'end_time',
            'venue_id', 'expected_guests', 'budget', 'status'
        ));

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function updateStatus($id, $status)
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => $status]);

        return redirect()->back()->with('success', 'Event status updated.');
    }

    public function calendar()
    {
        $events = Event::where('event_date', '>=', now()->startOfMonth()->subMonth())
            ->where('event_date', '<=', now()->endOfMonth()->addMonth())
            ->get();

        return view('admin.events.calendar', compact('events'));
    }
}
