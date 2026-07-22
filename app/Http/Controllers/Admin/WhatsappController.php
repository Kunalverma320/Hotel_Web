<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappLog;
use App\Models\WhatsappSetting;

class WhatsappController extends Controller
{
    public function logs()
    {
        $logs = WhatsappLog::latest()->paginate(20);

        return view('admin.whatsapp.logs', compact('logs'));
    }

    public function settings()
    {
        $settings = WhatsappSetting::firstOrCreate(['id' => 1]);

        return view('admin.whatsapp.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'api_url' => 'nullable|string|max:500',
            'api_token' => 'nullable|string|max:255',
            'phone_number_id' => 'nullable|string|max:50',
            'business_account_id' => 'nullable|string|max:50',
            'verify_token' => 'nullable|string|max:255',
        ]);

        $settings = WhatsappSetting::firstOrCreate(['id' => 1]);
        $settings->update($request->only('api_url', 'api_token', 'phone_number_id', 'business_account_id', 'verify_token'));

        return back()->with('success', 'WhatsApp settings updated successfully.');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:4096',
        ]);

        $status = 'sent';
        $error = null;

        try {
            $setting = WhatsappSetting::first();
            if (!$setting || !$setting->api_token) {
                throw new \Exception('WhatsApp API not configured.');
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        WhatsappLog::create([
            'to' => $request->phone,
            'message' => $request->message,
            'status' => $status,
            'error' => $error,
            'sent_at' => now(),
        ]);

        if ($status === 'sent') {
            return back()->with('success', 'WhatsApp message sent successfully.');
        }

        return back()->with('error', 'Failed to send message: ' . $error);
    }
}
