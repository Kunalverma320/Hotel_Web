<form method="POST" action="{{ route('admin.settings.email.update') }}">
    @csrf
    @method('PUT')
    <h5 class="mb-3">Email Settings</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Mail Driver</label>
            <select name="mail_driver" class="form-select" required>
                <option value="smtp">SMTP</option>
                <option value="sendmail">Sendmail</option>
                <option value="mailgun">Mailgun</option>
                <option value="ses">Amazon SES</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Host</label>
            <input type="text" name="mail_host" class="form-control" value="{{ old('mail_host') }}" required placeholder="smtp.gmail.com">
        </div>
        <div class="col-md-3">
            <label class="form-label">Port</label>
            <input type="number" name="mail_port" class="form-control" value="{{ old('mail_port', 585) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" name="mail_username" class="form-control" value="{{ old('mail_username') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="mail_password" class="form-control" value="{{ old('mail_password') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Encryption</label>
            <select name="mail_encryption" class="form-select" required>
                <option value="tls">TLS</option>
                <option value="ssl">SSL</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">From Address</label>
            <input type="email" name="mail_from_address" class="form-control" value="{{ old('mail_from_address') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">From Name</label>
            <input type="text" name="mail_from_name" class="form-control" value="{{ old('mail_from_name') }}" required>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
        <button type="button" class="btn btn-outline-secondary" onclick="testEmail()"><i class="bi bi-send"></i> Send Test Email</button>
    </div>
</form>

<script>
function testEmail() {
    fetch('{{ route("admin.settings.email.update") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }, body: JSON.stringify({ test: true }) })
        .then(r => r.json()).then(d => alert(d.message || 'Test email sent!')).catch(() => alert('Failed to send test email.'));
}
</script>
