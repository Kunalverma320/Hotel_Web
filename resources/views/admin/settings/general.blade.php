<form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <h5 class="mb-3">General Settings</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Application Name</label>
            <input type="text" name="app_name" class="form-control" value="{{ old('app_name', config('app.name', 'Hotel Management')) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@hotel.com">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 234 567 890">
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="123 Hotel Street, City">
        </div>
        <div class="col-md-6">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            <small class="text-muted">Recommended: 200x60px, PNG or SVG</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">Favicon</label>
            <input type="file" name="favicon" class="form-control" accept="image/*">
            <small class="text-muted">Recommended: 32x32px, ICO or PNG</small>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
    </div>
</form>
