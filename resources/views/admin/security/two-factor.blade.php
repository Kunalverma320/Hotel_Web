@extends('admin.layouts.app')
@section('title', 'Two-Factor Authentication')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-shield-lock"></i> Two-Factor Authentication</h1>
    <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">2FA Status</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-success fs-6">2FA is currently <strong>ENABLED</strong></span>
                </div>
                <p>Two-factor authentication adds an extra layer of security to your account by requiring a verification code in addition to your password.</p>

                <div id="enableSection" style="display:none;">
                    <h6>Setup 2FA</h6>
                    <div class="text-center mb-3 p-3 bg-light rounded">
                        <canvas id="qrCanvas" width="200" height="200"></canvas>
                        <p class="small text-muted mt-2">Scan this QR code with your authenticator app</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Or enter this secret key manually:</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" value="JBSWY3DPEHPK3PXP" readonly>
                            <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('JBSWY3DPEHPK3PXP')"><i class="bi bi-clipboard"></i></button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.security.2fa.enable') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Enter verification code from your app</label>
                            <input type="text" name="code" class="form-control" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-shield-check"></i> Enable 2FA</button>
                    </form>
                </div>

                <div id="disableSection">
                    <hr>
                    <h6>Disable 2FA</h6>
                    <form method="POST" action="{{ route('admin.security.2fa.disable') }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label">Enter your password to confirm</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to disable 2FA?')"><i class="bi bi-shield-x"></i> Disable 2FA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Recovery Codes</h5></div>
            <div class="card-body">
                <p class="text-muted">Save these recovery codes in a safe place. You can use them to access your account if you lose your authenticator device.</p>
                <div class="bg-light p-3 rounded font-monospace">
                    <div class="row">
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                        <div class="col-6">XXXX-XXXX</div>
                    </div>
                </div>
                <button class="btn btn-outline-secondary btn-sm mt-3"><i class="bi bi-download"></i> Download Codes</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const canvas = document.getElementById('qrCanvas');
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#f0f0f0';
    ctx.fillRect(0, 0, 200, 200);
    ctx.fillStyle = '#333';
    ctx.font = '14px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('QR Code Placeholder', 100, 100);
    ctx.fillText('Use a library like', 100, 120);
    ctx.fillText('bacon/bacon-qr-code', 100, 140);
</script>
@endpush
@endsection
