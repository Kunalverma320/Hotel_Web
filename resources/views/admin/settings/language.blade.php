<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Language Management</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#languageModal" onclick="resetLanguageForm()"><i class="bi bi-plus"></i> Add Language</button>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Name</th><th>Code</th><th>Default</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <tr><td colspan="5" class="text-center text-muted">No languages configured yet.</td></tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="languageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="languageForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="languageModalTitle">Add Language</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="English">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" required maxlength="2" placeholder="en">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="default" class="form-check-input" value="1" id="langDefault">
                                <label class="form-check-label" for="langDefault">Set as Default</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetLanguageForm() {
    document.getElementById('languageForm').reset();
    document.getElementById('languageForm').action = '{{ route("admin.settings.language.store") }}';
    document.getElementById('languageModalTitle').textContent = 'Add Language';
}
</script>
