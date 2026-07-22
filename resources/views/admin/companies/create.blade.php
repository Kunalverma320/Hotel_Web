@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Create Company</h4>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

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
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number') }}">
                            @error('gst_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" name="pan_number" id="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}">
                            @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="currency" class="form-label">Currency</label>
                            <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror">
                                <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>AED - UAE Dirham</option>
                            </select>
                            @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                                <option value="Asia/Kolkata" {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                <option value="America/Los_Angeles" {{ old('timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                                <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                <option value="Asia/Dubai" {{ old('timezone') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                            </select>
                            @error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                            @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                            @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="zipcode" class="form-label">Zipcode</label>
                            <input type="text" name="zipcode" id="zipcode" class="form-control @error('zipcode') is-invalid @enderror" value="{{ old('zipcode') }}">
                            @error('zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
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
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website') }}">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}">
                            @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <div class="col-md-4">
                            <label for="smtp_host" class="form-label">SMTP Host</label>
                            <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ old('smtp_host') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_port" class="form-label">SMTP Port</label>
                            <input type="number" name="smtp_port" id="smtp_port" class="form-control" value="{{ old('smtp_port', 587) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="smtp_encryption" class="form-label">Encryption</label>
                            <select name="smtp_encryption" id="smtp_encryption" class="form-select">
                                <option value="tls" {{ old('smtp_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_username" class="form-label">Username</label>
                            <input type="email" name="smtp_username" id="smtp_username" class="form-control" value="{{ old('smtp_username') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_password" class="form-label">Password</label>
                            <input type="password" name="smtp_password" id="smtp_password" class="form-control" value="{{ old('smtp_password') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_from_address" class="form-label">From Address</label>
                            <input type="email" name="smtp_from_address" id="smtp_from_address" class="form-control" value="{{ old('smtp_from_address') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="smtp_from_name" class="form-label">From Name</label>
                            <input type="text" name="smtp_from_name" id="smtp_from_name" class="form-control" value="{{ old('smtp_from_name') }}">
                        </div>
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
                            <label for="sms_provider" class="form-label">Provider</label>
                            <select name="sms_provider" id="sms_provider" class="form-select">
                                <option value="twilio" {{ old('sms_provider') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="nexmo" {{ old('sms_provider') == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                                <option value="custom" {{ old('sms_provider') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sms_api_key" class="form-label">API Key</label>
                            <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" value="{{ old('sms_api_key') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="sms_api_secret" class="form-label">API Secret</label>
                            <input type="password" name="sms_api_secret" id="sms_api_secret" class="form-control" value="{{ old('sms_api_secret') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="sms_sender_id" class="form-label">Sender ID</label>
                            <input type="text" name="sms_sender_id" id="sms_sender_id" class="form-control" value="{{ old('sms_sender_id') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="sms_api_url" class="form-label">API URL</label>
                            <input type="url" name="sms_api_url" id="sms_api_url" class="form-control" value="{{ old('sms_api_url') }}">
                        </div>
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
                                <input class="form-check-input" type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" {{ old('whatsapp_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="whatsapp_enabled">Enable WhatsApp Integration</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp_api_url" class="form-label">API URL</label>
                            <input type="url" name="whatsapp_api_url" id="whatsapp_api_url" class="form-control" value="{{ old('whatsapp_api_url') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp_api_token" class="form-label">API Token</label>
                            <input type="password" name="whatsapp_api_token" id="whatsapp_api_token" class="form-control" value="{{ old('whatsapp_api_token') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp_phone_number_id" class="form-label">Phone Number ID</label>
                            <input type="text" name="whatsapp_phone_number_id" id="whatsapp_phone_number_id" class="form-control" value="{{ old('whatsapp_phone_number_id') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp_business_account_id" class="form-label">Business Account ID</label>
                            <input type="text" name="whatsapp_business_account_id" id="whatsapp_business_account_id" class="form-control" value="{{ old('whatsapp_business_account_id') }}">
                        </div>
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
                            <label for="payment_gateway" class="form-label">Gateway</label>
                            <select name="payment_gateway" id="payment_gateway" class="form-select">
                                <option value="stripe" {{ old('payment_gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ old('payment_gateway') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="razorpay" {{ old('payment_gateway') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                <option value="manual" {{ old('payment_gateway') == 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="stripe_key" class="form-label">Stripe Key</label>
                            <input type="text" name="stripe_key" id="stripe_key" class="form-control" value="{{ old('stripe_key') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="stripe_secret" class="form-label">Stripe Secret</label>
                            <input type="password" name="stripe_secret" id="stripe_secret" class="form-control" value="{{ old('stripe_secret') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                            <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{ old('paypal_client_id') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="paypal_secret" class="form-label">PayPal Secret</label>
                            <input type="password" name="paypal_secret" id="paypal_secret" class="form-control" value="{{ old('paypal_secret') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="razorpay_key" class="form-label">Razorpay Key</label>
                            <input type="text" name="razorpay_key" id="razorpay_key" class="form-control" value="{{ old('razorpay_key') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="razorpay_secret" class="form-label">Razorpay Secret</label>
                            <input type="password" name="razorpay_secret" id="razorpay_secret" class="form-control" value="{{ old('razorpay_secret') }}">
                        </div>
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
                                <input class="form-check-input" type="checkbox" name="google_maps_enabled" id="google_maps_enabled" value="1" {{ old('google_maps_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="google_maps_enabled">Enable Google Maps</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="google_maps_api_key" class="form-label">API Key</label>
                            <input type="text" name="google_maps_api_key" id="google_maps_api_key" class="form-control" value="{{ old('google_maps_api_key') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Save Company</button>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection
