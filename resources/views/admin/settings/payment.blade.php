<form method="POST" action="{{ route('admin.settings.payment.update') }}">
    @csrf
    @method('PUT')
    <h5 class="mb-3">Payment Gateway Settings</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Payment Gateway</label>
            <select name="payment_gateway" class="form-select" id="gatewaySelect" required>
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
                <option value="square">Square</option>
                <option value="razorpay">Razorpay</option>
                <option value="manual">Manual / Offline</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Currency</label>
            <input type="text" name="currency" class="form-control" value="{{ old('currency', 'USD') }}" required maxlength="3">
        </div>
    </div>

    <div id="stripeFields" class="mt-3">
        <h6>Stripe Configuration</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Stripe Publishable Key</label>
                <input type="text" name="stripe_key" class="form-control" value="{{ old('stripe_key') }}" placeholder="pk_test_...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Stripe Secret Key</label>
                <input type="password" name="stripe_secret" class="form-control" value="{{ old('stripe_secret') }}" placeholder="sk_test_...">
            </div>
        </div>
    </div>

    <div id="paypalFields" class="mt-3" style="display:none;">
        <h6>PayPal Configuration</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Client ID</label>
                <input type="text" name="paypal_client_id" class="form-control" value="{{ old('paypal_client_id') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Secret</label>
                <input type="password" name="paypal_secret" class="form-control" value="{{ old('paypal_secret') }}">
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
    </div>
</form>

<script>
document.getElementById('gatewaySelect').addEventListener('change', function() {
    document.getElementById('stripeFields').style.display = this.value === 'stripe' ? 'block' : 'none';
    document.getElementById('paypalFields').style.display = this.value === 'paypal' ? 'block' : 'none';
});
</script>
