@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Company: {{ $company->name }}</h4>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-basic">Basic Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-contact">Contact Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-smtp">SMTP Settings</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-sms">SMS Settings</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-whatsapp">WhatsApp</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-payment">Payment Gateway</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-maps">Google Maps</a></li>
    </ul>

    <div class="tab-content">
        {{-- Basic Info Tab --}}
        <div class="tab-pane fade show active" id="tab-basic">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Basic Information</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $company->slug) }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="logo" class="form-label">Logo</label>
                            @if($company->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" class="rounded" style="height: 60px;">
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="form-control" value="{{ old('gst_number', $company->gst_number) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" name="pan_number" id="pan_number" class="form-control" value="{{ old('pan_number', $company->pan_number) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="currency" class="form-label">Currency</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="INR" {{ old('currency', $company->currency) == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                <option value="USD" {{ old('currency', $company->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency', $company->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency', $company->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="AED" {{ old('currency', $company->currency) == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select name="timezone" id="timezone" class="form-select">
                                <option value="Asia/Kolkata" {{ old('timezone', $company->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                <option value="America/New_York" {{ old('timezone', $company->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                <option value="America/Los_Angeles" {{ old('timezone', $company->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                                <option value="Europe/London" {{ old('timezone', $company->timezone) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                <option value="Asia/Dubai" {{ old('timezone', $company->timezone) == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $company->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $company->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $company->address) }}</textarea>
                        </div>
                        <div class="col-md-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $company->city) }}"></div>
                        <div class="col-md-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $company->state) }}"></div>
                        <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', $company->country) }}"></div>
                        <div class="col-md-3"><label class="form-label">Zipcode</label><input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $company->zipcode) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Info Tab --}}
        <div class="tab-pane fade" id="tab-contact">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Contact Information</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $company->contact_person) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SMTP Tab --}}
        <div class="tab-pane fade" id="tab-smtp">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">SMTP Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">SMTP Host</label><input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $company->smtp_host) }}"></div>
                        <div class="col-md-4"><label class="form-label">SMTP Port</label><input type="number" name="smtp_port" class="form-control" value="{{ old('smtp_port', $company->smtp_port ?? 587) }}"></div>
                        <div class="col-md-4">
                            <label class="form-label">Encryption</label>
                            <select name="smtp_encryption" class="form-select">
                                <option value="tls" {{ old('smtp_encryption', $company->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption', $company->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Username</label><input type="email" name="smtp_username" class="form-control" value="{{ old('smtp_username', $company->smtp_username) }}"></div>
                        <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="smtp_password" class="form-control" value="{{ old('smtp_password', $company->smtp_password) }}"></div>
                        <div class="col-md-6"><label class="form-label">From Address</label><input type="email" name="smtp_from_address" class="form-control" value="{{ old('smtp_from_address', $company->smtp_from_address) }}"></div>
                        <div class="col-md-6"><label class="form-label">From Name</label><input type="text" name="smtp_from_name" class="form-control" value="{{ old('smtp_from_name', $company->smtp_from_name) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SMS Tab --}}
        <div class="tab-pane fade" id="tab-sms">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">SMS Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Provider</label>
                            <select name="sms_provider" class="form-select">
                                <option value="twilio" {{ old('sms_provider', $company->sms_provider) == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="nexmo" {{ old('sms_provider', $company->sms_provider) == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                                <option value="custom" {{ old('sms_provider', $company->sms_provider) == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label">API Key</label><input type="text" name="sms_api_key" class="form-control" value="{{ old('sms_api_key', $company->sms_api_key) }}"></div>
                        <div class="col-md-4"><label class="form-label">API Secret</label><input type="password" name="sms_api_secret" class="form-control" value="{{ old('sms_api_secret', $company->sms_api_secret) }}"></div>
                        <div class="col-md-6"><label class="form-label">Sender ID</label><input type="text" name="sms_sender_id" class="form-control" value="{{ old('sms_sender_id', $company->sms_sender_id) }}"></div>
                        <div class="col-md-6"><label class="form-label">API URL</label><input type="url" name="sms_api_url" class="form-control" value="{{ old('sms_api_url', $company->sms_api_url) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- WhatsApp Tab --}}
        <div class="tab-pane fade" id="tab-whatsapp">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">WhatsApp Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" {{ old('whatsapp_enabled', $company->whatsapp_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label">Enable WhatsApp Integration</label>
                            </div>
                        </div>
                        <div class="col-md-6"><label class="form-label">API URL</label><input type="url" name="whatsapp_api_url" class="form-control" value="{{ old('whatsapp_api_url', $company->whatsapp_api_url) }}"></div>
                        <div class="col-md-6"><label class="form-label">API Token</label><input type="password" name="whatsapp_api_token" class="form-control" value="{{ old('whatsapp_api_token', $company->whatsapp_api_token) }}"></div>
                        <div class="col-md-6"><label class="form-label">Phone Number ID</label><input type="text" name="whatsapp_phone_number_id" class="form-control" value="{{ old('whatsapp_phone_number_id', $company->whatsapp_phone_number_id) }}"></div>
                        <div class="col-md-6"><label class="form-label">Business Account ID</label><input type="text" name="whatsapp_business_account_id" class="form-control" value="{{ old('whatsapp_business_account_id', $company->whatsapp_business_account_id) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Gateway Tab --}}
        <div class="tab-pane fade" id="tab-payment">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Payment Gateway Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Gateway</label>
                            <select name="payment_gateway" class="form-select">
                                <option value="stripe" {{ old('payment_gateway', $company->payment_gateway) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ old('payment_gateway', $company->payment_gateway) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="razorpay" {{ old('payment_gateway', $company->payment_gateway) == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                <option value="manual" {{ old('payment_gateway', $company->payment_gateway) == 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Stripe Key</label><input type="text" name="stripe_key" class="form-control" value="{{ old('stripe_key', $company->stripe_key) }}"></div>
                        <div class="col-md-6"><label class="form-label">Stripe Secret</label><input type="password" name="stripe_secret" class="form-control" value="{{ old('stripe_secret', $company->stripe_secret) }}"></div>
                        <div class="col-md-6"><label class="form-label">PayPal Client ID</label><input type="text" name="paypal_client_id" class="form-control" value="{{ old('paypal_client_id', $company->paypal_client_id) }}"></div>
                        <div class="col-md-6"><label class="form-label">PayPal Secret</label><input type="password" name="paypal_secret" class="form-control" value="{{ old('paypal_secret', $company->paypal_secret) }}"></div>
                        <div class="col-md-6"><label class="form-label">Razorpay Key</label><input type="text" name="razorpay_key" class="form-control" value="{{ old('razorpay_key', $company->razorpay_key) }}"></div>
                        <div class="col-md-6"><label class="form-label">Razorpay Secret</label><input type="password" name="razorpay_secret" class="form-control" value="{{ old('razorpay_secret', $company->razorpay_secret) }}"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Google Maps Tab --}}
        <div class="tab-pane fade" id="tab-maps">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Google Maps Settings</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="google_maps_enabled" value="1" {{ old('google_maps_enabled', $company->google_maps_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label">Enable Google Maps</label>
                            </div>
                        </div>
                        <div class="col-md-6"><label class="form-label">API Key</label><input type="text" name="google_maps_api_key" class="form-control" value="{{ old('google_maps_api_key', $company->google_maps_api_key) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Update Company</button>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection
