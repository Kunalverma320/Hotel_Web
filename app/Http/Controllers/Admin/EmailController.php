<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function templates()
    {
        $templates = EmailTemplate::latest()->paginate(15);

        return view('admin.emails.templates', compact('templates'));
    }

    public function templateCreate()
    {
        return view('admin.emails.template-form');
    }

    public function templateStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:email_templates,slug',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        EmailTemplate::create($request->only('name', 'slug', 'subject', 'body'));

        return redirect()->route('admin.emails.templates')->with('success', 'Email template created successfully.');
    }

    public function templateEdit($id)
    {
        $template = EmailTemplate::findOrFail($id);

        return view('admin.emails.template-form', compact('template'));
    }

    public function templateUpdate(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:email_templates,slug,' . $template->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($request->only('name', 'slug', 'subject', 'body'));

        return redirect()->route('admin.emails.templates')->with('success', 'Email template updated successfully.');
    }

    public function templateDestroy($id)
    {
        EmailTemplate::findOrFail($id)->delete();

        return redirect()->route('admin.emails.templates')->with('success', 'Email template deleted successfully.');
    }

    public function logs()
    {
        $logs = EmailLog::latest()->paginate(20);

        return view('admin.emails.logs', compact('logs'));
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from Hotel Management System.', function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Email - Hotel Management');
            });

            EmailLog::create([
                'to' => $request->email,
                'subject' => 'Test Email - Hotel Management',
                'body' => 'This is a test email from Hotel Management System.',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return back()->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            EmailLog::create([
                'to' => $request->email,
                'subject' => 'Test Email - Hotel Management',
                'body' => 'This is a test email from Hotel Management System.',
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function smtpSettings()
    {
        $settings = SmtpSetting::firstOrCreate(['id' => 1]);

        return view('admin.emails.smtp', compact('settings'));
    }

    public function updateSmtpSettings(Request $request)
    {
        $request->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|in:tls,ssl,none',
            'from_address' => 'required|email|max:255',
            'from_name' => 'nullable|string|max:255',
        ]);

        $settings = SmtpSetting::firstOrCreate(['id' => 1]);
        $settings->update($request->only('host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name'));

        return back()->with('success', 'SMTP settings updated successfully.');
    }
}
