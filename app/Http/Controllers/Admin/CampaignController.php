<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.marketing.campaigns', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.marketing.campaign-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,whatsapp,push',
            'subject' => 'required_if:type,email|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'nullable|array',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['status'] = 'draft';
        Campaign::create($validated);
        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign created.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.marketing.campaign-form', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,whatsapp,push',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'nullable|array',
        ]);
        $campaign->update($validated);
        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign deleted.');
    }
}
