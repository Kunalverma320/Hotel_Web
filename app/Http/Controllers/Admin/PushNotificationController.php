<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function index()
    {
        $notifications = PushNotification::latest()->paginate(20);
        return view('admin.marketing.push-notifications', compact('notifications'));
    }

    public function create()
    {
        return view('admin.marketing.push-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target' => 'required|in:all,guests,employees,custom',
            'data' => 'nullable|array',
        ]);
        $validated['status'] = 'draft';
        PushNotification::create($validated);
        return redirect()->route('admin.marketing.push-notifications.index')->with('success', 'Push notification created.');
    }

    public function show(PushNotification $notification)
    {
        return view('admin.marketing.push-form', ['notification' => $notification]);
    }

    public function send(PushNotification $notification)
    {
        $notification->update(['status' => 'sent', 'sent_at' => now()]);
        return back()->with('success', 'Push notification sent.');
    }
}
