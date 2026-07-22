<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index');
    }

    public function markAsRead($id)
    {
        // TODO: Mark notification as read

        return redirect()->route('admin.notifications.index')->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        // TODO: Mark all notifications as read for current user

        return redirect()->route('admin.notifications.index')->with('success', 'All notifications marked as read.');
    }

    public function settings()
    {
        return view('admin.notifications.settings');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_booking'       => 'nullable|boolean',
            'email_checkin'       => 'nullable|boolean',
            'email_checkout'      => 'nullable|boolean',
            'email_cancellation'  => 'nullable|boolean',
            'email_payment'       => 'nullable|boolean',
            'email_system'        => 'nullable|boolean',
            'sms_booking'         => 'nullable|boolean',
            'sms_checkin'         => 'nullable|boolean',
            'sms_checkout'        => 'nullable|boolean',
            'sms_cancellation'    => 'nullable|boolean',
            'push_booking'        => 'nullable|boolean',
            'push_checkin'        => 'nullable|boolean',
            'push_checkout'       => 'nullable|boolean',
            'push_system'         => 'nullable|boolean',
        ]);

        // TODO: Save notification settings to database

        return redirect()->route('admin.notifications.settings')->with('success', 'Notification settings updated successfully.');
    }
}
