<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        return view('admin.settings.general');
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'app_name'  => 'required|string|max:255',
            'address'   => 'nullable|string|max:500',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon'   => 'nullable|image|mimes:png,ico,svg|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        // TODO: Save settings to database

        return redirect()->route('admin.settings.general')->with('success', 'General settings updated successfully.');
    }

    public function tax()
    {
        return view('admin.settings.tax');
    }

    public function taxCreate()
    {
        return view('admin.settings.tax');
    }

    public function taxStore(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'rate'   => 'required|numeric|min:0|max:100',
            'type'   => 'required|in:percentage,fixed',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Save tax to database

        return redirect()->route('admin.settings.tax')->with('success', 'Tax rate created successfully.');
    }

    public function taxEdit($id)
    {
        // TODO: Fetch tax by id
        $tax = (object) ['id' => $id, 'name' => '', 'rate' => 0, 'type' => 'percentage', 'status' => 'active'];

        return view('admin.settings.tax', compact('tax'));
    }

    public function taxUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'rate'   => 'required|numeric|min:0|max:100',
            'type'   => 'required|in:percentage,fixed',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Update tax in database

        return redirect()->route('admin.settings.tax')->with('success', 'Tax rate updated successfully.');
    }

    public function taxDestroy($id)
    {
        // TODO: Delete tax from database

        return redirect()->route('admin.settings.tax')->with('success', 'Tax rate deleted successfully.');
    }

    public function currency()
    {
        return view('admin.settings.currency');
    }

    public function currencyCreate()
    {
        return view('admin.settings.currency');
    }

    public function currencyStore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|size:3|unique:currencies,code',
            'symbol'   => 'required|string|max:10',
            'exchange' => 'required|numeric|min:0',
            'status'   => 'required|in:active,inactive',
        ]);

        // TODO: Save currency to database

        return redirect()->route('admin.settings.currency')->with('success', 'Currency created successfully.');
    }

    public function currencyEdit($id)
    {
        $currency = (object) ['id' => $id, 'name' => '', 'code' => '', 'symbol' => '', 'exchange' => 1, 'status' => 'active'];

        return view('admin.settings.currency', compact('currency'));
    }

    public function currencyUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|size:3',
            'symbol'   => 'required|string|max:10',
            'exchange' => 'required|numeric|min:0',
            'status'   => 'required|in:active,inactive',
        ]);

        // TODO: Update currency in database

        return redirect()->route('admin.settings.currency')->with('success', 'Currency updated successfully.');
    }

    public function currencyDestroy($id)
    {
        // TODO: Delete currency from database

        return redirect()->route('admin.settings.currency')->with('success', 'Currency deleted successfully.');
    }

    public function language()
    {
        return view('admin.settings.language');
    }

    public function languageCreate()
    {
        return view('admin.settings.language');
    }

    public function languageStore(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|size:2|unique:languages,code',
            'status'  => 'required|in:active,inactive',
            'default' => 'nullable|boolean',
        ]);

        // TODO: Save language to database

        return redirect()->route('admin.settings.language')->with('success', 'Language created successfully.');
    }

    public function languageEdit($id)
    {
        $language = (object) ['id' => $id, 'name' => '', 'code' => '', 'status' => 'active', 'default' => false];

        return view('admin.settings.language', compact('language'));
    }

    public function languageUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|size:2',
            'status'  => 'required|in:active,inactive',
            'default' => 'nullable|boolean',
        ]);

        // TODO: Update language in database

        return redirect()->route('admin.settings.language')->with('success', 'Language updated successfully.');
    }

    public function languageDestroy($id)
    {
        // TODO: Delete language from database

        return redirect()->route('admin.settings.language')->with('success', 'Language deleted successfully.');
    }

    public function emailSettings()
    {
        return view('admin.settings.email');
    }

    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'mail_driver'   => 'required|in:smtp,sendmail,mailgun,ses',
            'mail_host'     => 'required|string|max:255',
            'mail_port'     => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        // TODO: Save email settings to database/config

        return redirect()->route('admin.settings.email')->with('success', 'Email settings updated successfully.');
    }

    public function smsSettings()
    {
        return view('admin.settings.sms');
    }

    public function updateSmsSettings(Request $request)
    {
        $validated = $request->validate([
            'sms_provider' => 'required|in:twilio,nexmo,textlocal,custom',
            'sms_api_key'  => 'required|string|max:255',
            'sms_api_secret' => 'nullable|string|max:255',
            'sms_sender_id' => 'required|string|max:20',
            'sms_api_url'  => 'nullable|url|max:500',
        ]);

        // TODO: Save SMS settings to database

        return redirect()->route('admin.settings.sms')->with('success', 'SMS settings updated successfully.');
    }

    public function paymentSettings()
    {
        return view('admin.settings.payment');
    }

    public function updatePaymentSettings(Request $request)
    {
        $validated = $request->validate([
            'payment_gateway'    => 'required|in:stripe,paypal,square,razorpay,manual',
            'stripe_key'         => 'nullable|string|max:255',
            'stripe_secret'      => 'nullable|string|max:255',
            'paypal_client_id'   => 'nullable|string|max:255',
            'paypal_secret'      => 'nullable|string|max:255',
            'currency'           => 'required|string|size:3',
        ]);

        // TODO: Save payment settings to database

        return redirect()->route('admin.settings.payment')->with('success', 'Payment settings updated successfully.');
    }

    public function theme()
    {
        return view('admin.settings.theme');
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'primary_color'   => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'sidebar_style'   => 'required|in:dark,light,colored',
            'dark_mode'       => 'nullable|boolean',
            'sidebar_icon'    => 'nullable|boolean',
            'compact_sidebar' => 'nullable|boolean',
        ]);

        // TODO: Save theme settings to database

        return redirect()->route('admin.settings.theme')->with('success', 'Theme settings updated successfully.');
    }

    public function backup()
    {
        return view('admin.settings.backup');
    }

    public function createBackup(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:full,database,files',
        ]);

        // TODO: Create backup
        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.zip';

        return redirect()->route('admin.settings.backup')->with('success', "Backup '{$filename}' created successfully.");
    }

    public function restoreBackup($id)
    {
        // TODO: Restore backup by id

        return redirect()->route('admin.settings.backup')->with('success', 'Backup restored successfully.');
    }

    public function deleteBackup($id)
    {
        // TODO: Delete backup by id

        return redirect()->route('admin.settings.backup')->with('success', 'Backup deleted successfully.');
    }

    public function scheduleBackup(Request $request)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly',
            'time'      => 'required|date_format:H:i',
            'keep_days' => 'required|integer|min:1|max:365',
            'enabled'   => 'nullable|boolean',
        ]);

        // TODO: Save backup schedule to database

        return redirect()->route('admin.settings.backup')->with('success', 'Backup schedule updated successfully.');
    }
}
