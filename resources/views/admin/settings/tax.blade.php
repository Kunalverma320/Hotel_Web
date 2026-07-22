<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Tax Rate Management</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#taxModal" onclick="resetTaxForm()"><i class="bi bi-plus"></i> Add Tax Rate</button>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Name</th><th>Rate</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <tr><td colspan="5" class="text-center text-muted">No tax rates configured yet.</td></tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="taxModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="taxForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="taxModalTitle">Add Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Rate (%)</label>
                            <input type="number" name="rate" class="form-control" step="0.01" min="0" max="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
function resetTaxForm() {
    document.getElementById('taxForm').reset();
    document.getElementById('taxForm').action = '{{ route("admin.settings.tax.store") }}';
    document.getElementById('taxModalTitle').textContent = 'Add Tax Rate';
}
function editTax(id, name, rate, type, status) {
    document.getElementById('taxForm').action = '{{ url("admin/settings/tax") }}/' + id;
    document.getElementById('taxModalTitle').textContent = 'Edit Tax Rate';
    document.getElementById('taxForm').name.value = name;
    document.getElementById('taxForm').rate.value = rate;
    document.getElementById('taxForm').type.value = type;
    document.getElementById('taxForm').status.value = status;
}
</script>
