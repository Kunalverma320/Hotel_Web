<form method="POST" action="{{ route('admin.settings.theme.update') }}">
    @csrf
    @method('PUT')
    <h5 class="mb-3">Theme Settings</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Primary Color</label>
            <input type="color" name="primary_color" class="form-control form-control-color" value="{{ old('primary_color', '#0d6efd') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Secondary Color</label>
            <input type="color" name="secondary_color" class="form-control form-control-color" value="{{ old('secondary_color', '#6c757d') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sidebar Style</label>
            <select name="sidebar_style" class="form-select" required>
                <option value="dark">Dark</option>
                <option value="light">Light</option>
                <option value="colored">Colored</option>
            </select>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch mt-4">
                <input type="checkbox" name="dark_mode" class="form-check-input" value="1" id="darkMode" {{ old('dark_mode') ? 'checked' : '' }}>
                <label class="form-check-label" for="darkMode">Dark Mode Default</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch mt-4">
                <input type="checkbox" name="sidebar_icon" class="form-check-input" value="1" id="sidebarIcon" {{ old('sidebar_icon', 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="sidebarIcon">Show Sidebar Icons</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch mt-4">
                <input type="checkbox" name="compact_sidebar" class="form-check-input" value="1" id="compactSidebar" {{ old('compact_sidebar') ? 'checked' : '' }}>
                <label class="form-check-label" for="compactSidebar">Compact Sidebar</label>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Theme</button>
    </div>
</form>
