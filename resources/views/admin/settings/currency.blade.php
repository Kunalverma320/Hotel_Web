<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Currency Management</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#currencyModal" onclick="resetCurrencyForm()"><i class="bi bi-plus"></i> Add Currency</button>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Name</th><th>Code</th><th>Symbol</th><th>Exchange Rate</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <tr><td colspan="6" class="text-center text-muted">No currencies configured yet.</td></tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="currencyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="currencyForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="currencyModalTitle">Add Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="US Dollar">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" required maxlength="3" placeholder="USD">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Symbol</label>
                            <input type="text" name="symbol" class="form-control" required maxlength="10" placeholder="$">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Exchange Rate (to USD)</label>
                            <input type="number" name="exchange" class="form-control" step="0.0001" min="0" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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
function resetCurrencyForm() {
    document.getElementById('currencyForm').reset();
    document.getElementById('currencyForm').action = '{{ route("admin.settings.currency.store") }}';
    document.getElementById('currencyModalTitle').textContent = 'Add Currency';
}
</script>
