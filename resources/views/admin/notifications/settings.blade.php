@extends('admin.layouts.app')
@section('title', 'Notification Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-bell"></i> Notification Settings</h1>
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('admin.notifications.settings.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-envelope"></i> Email Notifications</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_booking" class="form-check-input" value="1" id="emailBooking" checked>
                        <label class="form-check-label" for="emailBooking">Booking Confirmation</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_checkin" class="form-check-input" value="1" id="emailCheckin" checked>
                        <label class="form-check-label" for="emailCheckin">Check-in Notification</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_checkout" class="form-check-input" value="1" id="emailCheckout" checked>
                        <label class="form-check-label" for="emailCheckout">Check-out Notification</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_cancellation" class="form-check-input" value="1" id="emailCancel" checked>
                        <label class="form-check-label" for="emailCancel">Cancellation Alert</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_payment" class="form-check-input" value="1" id="emailPayment" checked>
                        <label class="form-check-label" for="emailPayment">Payment Notification</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="email_system" class="form-check-input" value="1" id="emailSystem" checked>
                        <label class="form-check-label" for="emailSystem">System Alerts</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-phone"></i> SMS Notifications</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="sms_booking" class="form-check-input" value="1" id="smsBooking">
                        <label class="form-check-label" for="smsBooking">Booking Confirmation</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="sms_checkin" class="form-check-input" value="1" id="smsCheckin">
                        <label class="form-check-label" for="smsCheckin">Check-in Notification</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="sms_checkout" class="form-check-input" value="1" id="smsCheckout">
                        <label class="form-check-label" for="smsCheckout">Check-out Notification</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="sms_cancellation" class="form-check-input" value="1" id="smsCancel">
                        <label class="form-check-label" for="smsCancel">Cancellation Alert</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-bell"></i> Push Notifications</h6></div>
                <div class="card-body">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="push_booking" class="form-check-input" value="1" id="pushBooking" checked>
                        <label class="form-check-label" for="pushBooking">Booking Updates</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="push_checkin" class="form-check-input" value="1" id="pushCheckin" checked>
                        <label class="form-check-label" for="pushCheckin">Check-in Alert</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="push_checkout" class="form-check-input" value="1" id="pushCheckout" checked>
                        <label class="form-check-label" for="pushCheckout">Check-out Alert</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" name="push_system" class="form-check-input" value="1" id="pushSystem" checked>
                        <label class="form-check-label" for="pushSystem">System Alerts</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Preferences</button>
    </div>
</form>
@endsection
