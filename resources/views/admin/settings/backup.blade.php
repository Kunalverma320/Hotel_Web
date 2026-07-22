<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Backup Management</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBackupModal"><i class="bi bi-plus"></i> Create Backup Now</button>
</div>

<div class="card mb-4">
    <div class="card-header bg-white"><h6 class="mb-0">Backup Schedule</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.backup.schedule') }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Frequency</label>
                    <select name="frequency" class="form-select" required>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Time</label>
                    <input type="time" name="time" class="form-control" value="02:00" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Keep Backups (Days)</label>
                    <input type="number" name="keep_days" class="form-control" value="30" min="1" max="365" required>
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="enabled" class="form-check-input" value="1" id="scheduleEnabled" checked>
                        <label class="form-check-label" for="scheduleEnabled">Enabled</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check"></i> Save Schedule</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h6 class="mb-0">Existing Backups</h6></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Filename</th><th>Type</th><th>Size</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                    <tr><td colspan="5" class="text-center text-muted">No backups found.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.settings.backup.create') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create Backup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Backup Type</label>
                    <select name="type" class="form-select" required>
                        <option value="full">Full Backup (Database + Files)</option>
                        <option value="database">Database Only</option>
                        <option value="files">Files Only</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-hdd"></i> Create Backup</button>
                </div>
            </form>
        </div>
    </div>
</div>
