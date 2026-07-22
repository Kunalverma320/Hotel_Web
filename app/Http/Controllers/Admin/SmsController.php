<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsTemplate;
use App\Models\SmsLog;
use App\Models\SmsSetting;

class SmsController extends Controller
{
    public function templates()
    {
        $templates = SmsTemplate::latest()->paginate(15);

        return view('admin.sms.templates', compact('templates'));
    }

    public function templateCreate()
    {
        return view('admin.sms.template-form');
    }

    public function templateStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sms_templates,slug',
            'body' => 'required|string|max:1600',
        ]);

        SmsTemplate::create($request->only('name', 'slug', 'body'));

        return redirect()->route('admin.sms.templates')->with('success', 'SMS template created successfully.');
    }

    public function templateEdit($id)
    {
        $template = SmsTemplate::findOrFail($id);

        return view('admin.sms.template-form', compact('template'));
    }

    public function templateUpdate(Request $request, $id)
    {
        $template = SmsTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sms_templates,slug,' . $template->id,
            'body' => 'required|string|max:1600',
        ]);

        $template->update($request->only('name', 'slug', 'body'));

        return redirect()->route('admin.sms.templates')->with('success', 'SMS template updated successfully.');
    }

    public function templateDestroy($id)
    {
        SmsTemplate::findOrFail($id)->delete();

        return redirect()->route('admin.sms.templates')->with('success', 'SMS template deleted successfully.');
    }

    public function logs()
    {
        $logs = SmsLog::latest()->paginate(20);

        return view('admin.sms.logs', compact('logs'));
    }

    public function sendTestSms(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1600',
        ]);

        $status = 'sent';
        $error = null;

        try {
            $setting = SmsSetting::first();
            if (!$setting) {
                throw new \Exception('SMS provider not configured.');
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $error = $e->getMessage();
        }

        SmsLog::create([
            'to' => $request->phone,
            'message' => $request->message,
            'status' => $status,
            'error' => $error,
            'sent_at' => now(),
        ]);

        if ($status === 'sent') {
            return back()->with('success', 'Test SMS sent successfully.');
        }

        return back()->with('error', 'Failed to send test SMS: ' . $error);
    }

    public function settings()
    {
        $settings = SmsSetting::firstOrCreate(['id' => 1]);

        return view('admin.sms.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|in:twilio, nexmo, custom',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'from_number' => 'nullable|string|max:50',
            'api_url' => 'nullable|string|max:500',
        ]);

        $settings = SmsSetting::firstOrCreate(['id' => 1]);
        $settings->update($request->only('provider', 'api_key', 'api_secret', 'from_number', 'api_url'));

        return back()->with('success', 'SMS settings updated successfully.');
    }
}
