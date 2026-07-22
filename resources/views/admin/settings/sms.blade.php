<form method="POST" action="{{ route('admin.settings.sms.update') }}">
    @csrf
    @method('PUT')
    <h5 class="mb-3">SMS Settings</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">SMS Provider</label>
            <select name="sms_provider" class="form-select" required>
                <option value="twilio">Twilio</option>
                <option value="nexmo">Nexmo (Vonage)</option>
                <option value="textlocal">Textlocal</option>
                <option value="custom">Custom API</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Sender ID</label>
            <input type="text" name="sms_sender_id" class="form-control" value="{{ old('sms_sender_id') }}" required placeholder="HOTEL">
        </div>
        <div class="col-md-6">
            <label class="form-label">API Key</label>
            <input type="text" name="sms_api_key" class="form-control" value="{{ old('sms_api_key') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">API Secret</label>
            <input type="password" name="sms_api_secret" class="form-control" value="{{ old('sms_api_secret') }}">
        </div>
        <div class="col-md-12">
            <label class="form-label">API URL (Custom Provider)</label>
            <input type="url" name="sms_api_url" class="form-control" value="{{ old('sms_api_url') }}" placeholder="https://api.example.com/sms">
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
    </div>
</form>
