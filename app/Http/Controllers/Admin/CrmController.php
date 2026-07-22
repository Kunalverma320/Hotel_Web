<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Guest;
use App\Models\CustomerNote;
use App\Models\CustomerFollowup;
use App\Models\Campaign;
use App\Models\Hotel;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function leads(Request $request)
    {
        $query = Lead::with(['hotel', 'assignedTo']);

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('source')) {
            $query->bySource($request->source);
        }
        if ($request->filled('hotel_id')) {
            $query->byHotel($request->hotel_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(15);
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.crm.leads', compact('leads', 'hotels'));
    }

    public function leadCreate()
    {
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.crm.lead-create', compact('hotels'));
    }

    public function leadStore(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,converted,lost',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'check_in_date' => 'nullable|date',
            'check_out_date' => 'nullable|date|after_or_equal:check_in_date',
            'guests' => 'nullable|integer|min:1',
            'room_preference' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'next_followup_at' => 'nullable|date',
        ]);

        Lead::create($request->only([
            'hotel_id', 'first_name', 'last_name', 'email', 'phone', 'company',
            'source', 'status', 'priority', 'check_in_date', 'check_out_date',
            'guests', 'room_preference', 'budget', 'notes', 'assigned_to', 'next_followup_at',
        ]));

        return redirect()->route('admin.crm.leads')->with('success', 'Lead created successfully.');
    }

    public function leadEdit($id)
    {
        $lead = Lead::findOrFail($id);
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.crm.lead-edit', compact('lead', 'hotels'));
    }

    public function leadUpdate(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,converted,lost',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'check_in_date' => 'nullable|date',
            'check_out_date' => 'nullable|date|after_or_equal:check_in_date',
            'guests' => 'nullable|integer|min:1',
            'room_preference' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'next_followup_at' => 'nullable|date',
            'lost_reason' => 'nullable|string',
        ]);

        $data = $request->only([
            'hotel_id', 'first_name', 'last_name', 'email', 'phone', 'company',
            'source', 'status', 'priority', 'check_in_date', 'check_out_date',
            'guests', 'room_preference', 'budget', 'notes', 'assigned_to', 'next_followup_at',
        ]);

        if ($request->status === 'converted' && !$lead->converted_at) {
            $data['converted_at'] = now();
        }
        if ($request->status === 'lost' && !$lead->lost_at) {
            $data['lost_at'] = now();
            $data['lost_reason'] = $request->lost_reason;
        }

        $lead->update($data);

        return redirect()->route('admin.crm.leads')->with('success', 'Lead updated successfully.');
    }

    public function leadConvert($id)
    {
        $lead = Lead::findOrFail($id);

        $guest = Guest::create([
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company_name' => $lead->company,
        ]);

        $lead->update([
            'status' => 'converted',
            'converted_at' => now(),
        ]);

        return redirect()->route('admin.bookings.create', ['guest_id' => $guest->id])
            ->with('success', 'Lead converted to guest. You can now create a booking.');
    }

    public function leadDelete($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('admin.crm.leads')->with('success', 'Lead deleted successfully.');
    }

    public function notes($guestId)
    {
        $guest = Guest::with(['customerNotes.user'])->findOrFail($guestId);

        return view('admin.crm.notes', compact('guest'));
    }

    public function addNote(Request $request, $guestId)
    {
        $request->validate([
            'note' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_important' => 'boolean',
        ]);

        CustomerNote::create([
            'guest_id' => $guestId,
            'user_id' => auth()->id(),
            'note' => $request->note,
            'category' => $request->category,
            'is_important' => $request->boolean('is_important'),
        ]);

        return redirect()->route('admin.crm.notes', $guestId)->with('success', 'Note added successfully.');
    }

    public function followups(Request $request)
    {
        $query = CustomerFollowup::with(['lead', 'user']);

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } elseif ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'upcoming') {
                $query->upcoming();
            }
        }

        $followups = $query->latest()->paginate(15);

        return view('admin.crm.followups', compact('followups'));
    }

    public function addFollowup(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'type' => 'required|in:call,email,meeting,visit,other',
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'outcome' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ]);

        CustomerFollowup::create(array_merge(
            $request->only(['lead_id', 'type', 'subject', 'notes', 'outcome', 'next_followup_date', 'completed_at']),
            ['user_id' => auth()->id()]
        ));

        return redirect()->route('admin.crm.followups')->with('success', 'Follow-up added successfully.');
    }

    public function campaigns(Request $request)
    {
        $query = Campaign::with('hotel');

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        $campaigns = $query->latest()->paginate(15);

        return view('admin.crm.campaigns', compact('campaigns'));
    }

    public function createCampaign()
    {
        $hotels = Hotel::active()->orderBy('name')->get();

        return view('admin.crm.campaign-create', compact('hotels'));
    }

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,sms,social,search,promotion,referral,other',
            'status' => 'required|in:draft,scheduled,active,paused,completed,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_audience' => 'nullable|string|max:255',
            'channels' => 'nullable|array',
            'content' => 'nullable|string',
            'landing_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only([
            'hotel_id', 'name', 'description', 'type', 'status',
            'budget', 'start_date', 'end_date', 'target_audience',
            'channels', 'content', 'landing_url',
        ]);
        $data['tracking_code'] = strtoupper(\Illuminate\Support\Str::random(8));

        Campaign::create($data);

        return redirect()->route('admin.crm.campaigns')->with('success', 'Campaign created successfully.');
    }
}
