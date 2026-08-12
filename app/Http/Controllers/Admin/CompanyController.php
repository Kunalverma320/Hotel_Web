<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $statusVal = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;
            $query->where('status', $statusVal);
        }

        $companies = $query->latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'status' => 'required',
        ]);

        $data = $request->only([
            'name', 'slug', 'email', 'phone', 'website', 'address', 'city', 'state',
            'country', 'zipcode', 'gst_number', 'pan_number', 'currency_id', 'timezone_id',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
            'sms_provider', 'sms_api_key', 'sms_sender_id', 'whatsapp_api_key', 'whatsapp_phone_id',
            'google_maps_api_key', 'payment_gateway', 'payment_api_key', 'payment_merchant_id',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name) . '-' . time();
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        Company::create($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    public function show($id)
    {
        $company = Company::with('branches', 'hotels')->findOrFail($id);
        return view('admin.companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'status' => 'required',
        ]);

        $data = $request->only([
            'name', 'slug', 'email', 'phone', 'website', 'address', 'city', 'state',
            'country', 'zipcode', 'gst_number', 'pan_number', 'currency_id', 'timezone_id',
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
            'sms_provider', 'sms_api_key', 'sms_sender_id', 'whatsapp_api_key', 'whatsapp_phone_id',
            'google_maps_api_key', 'payment_gateway', 'payment_api_key', 'payment_merchant_id',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $data['status'] = in_array($request->status, ['active', '1', 1, true], true) ? 1 : 0;

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function updateSmtpSettings(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->only(['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption']));
        return back()->with('success', 'SMTP settings updated successfully.');
    }

    public function updateSmsSettings(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->only(['sms_provider', 'sms_api_key', 'sms_sender_id']));
        return back()->with('success', 'SMS settings updated successfully.');
    }

    public function updateWhatsAppSettings(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->only(['whatsapp_api_key', 'whatsapp_phone_id']));
        return back()->with('success', 'WhatsApp settings updated successfully.');
    }

    public function updatePaymentSettings(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->only(['payment_gateway', 'payment_api_key', 'payment_merchant_id']));
        return back()->with('success', 'Payment settings updated successfully.');
    }

    public function updateGoogleMaps(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->only(['google_maps_api_key']));
        return back()->with('success', 'Google Maps settings updated successfully.');
    }

    public function getBranches($id)
    {
        $branches = \App\Models\Branch::where('company_id', $id)->orderBy('name')->get(['id', 'name', 'code', 'company_id']);
        return response()->json($branches);
    }

    public function destroy($id)
    {
        Company::findOrFail($id)->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
}
