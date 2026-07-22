@extends('admin.layouts.app')

@section('title', 'Create Journal Entry')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Create Journal Entry</h4>
    <a href="{{ route('admin.finance.journal') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('admin.finance.journal-store') }}" method="POST" id="journalForm">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Entry Details</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Entry Number</label>
                            <input type="text" name="entry_number" class="form-control" value="{{ $entryNumber }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Entry Date <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                            @error('entry_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="Optional reference">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="2" required>{{ old('description') }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Journal Lines</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addLine">
                        <i class="fas fa-plus me-1"></i> Add Line
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <small><i class="fas fa-info-circle me-1"></i> Total Debit must equal Total Credit for the journal entry to be valid.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="linesTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Account <span class="text-danger">*</span></th>
                                    <th width="10%">Debit (₹)</th>
                                    <th width="10%">Credit (₹)</th>
                                    <th width="30%">Description</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="line-row">
                                    <td>
                                        <select name="lines[0][account_id]" class="form-select" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->code }} - {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="lines[0][debit]" class="form-control line-debit" step="0.01" min="0" value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="lines[0][credit]" class="form-control line-credit" step="0.01" min="0" value="0">
                                    </td>
                                    <td>
                                        <input type="text" name="lines[0][line_description]" class="form-control" placeholder="Optional">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-line">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="line-row">
                                    <td>
                                        <select name="lines[1][account_id]" class="form-select" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->code }} - {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="lines[1][debit]" class="form-control line-debit" step="0.01" min="0" value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="lines[1][credit]" class="form-control line-credit" step="0.01" min="0" value="0">
                                    </td>
                                    <td>
                                        <input type="text" name="lines[1][line_description]" class="form-control" placeholder="Optional">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-line">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td class="text-end">Totals:</td>
                                    <td>
                                        <span id="totalDebit" class="text-success">₹0.00</span>
                                    </td>
                                    <td>
                                        <span id="totalCredit" class="text-danger">₹0.00</span>
                                    </td>
                                    <td>
                                        <span id="balanceStatus" class="badge bg-secondary">Unbalanced</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @error('lines') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header"><h6 class="mb-0">Summary</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Debit:</span>
                        <strong class="text-success" id="summaryDebit">₹0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Credit:</span>
                        <strong class="text-danger" id="summaryCredit">₹0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Difference:</span>
                        <strong id="summaryDiff">₹0.00</strong>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Create Journal Entry
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let lineIndex = 2;

    document.getElementById('addLine').addEventListener('click', function() {
        const tbody = document.querySelector('#linesTable tbody');
        const newRow = document.querySelector('.line-row').cloneNode(true);
        newRow.querySelectorAll('select, input').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + lineIndex + ']');
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else if (el.type === 'number') el.value = '0';
            else el.value = '';
        });
        tbody.appendChild(newRow);
        lineIndex++;
        bindEvents();
    });

    function bindEvents() {
        document.querySelectorAll('.remove-line').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.line-row').length > 2) {
                    this.closest('tr').remove();
                    calculateTotals();
                }
            };
        });

        document.querySelectorAll('.line-debit, .line-credit').forEach(input => {
            input.oninput = function() {
                calculateTotals();
            };
        });
    }

    function calculateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;

        document.querySelectorAll('.line-debit').forEach(input => {
            totalDebit += parseFloat(input.value) || 0;
        });

        document.querySelectorAll('.line-credit').forEach(input => {
            totalCredit += parseFloat(input.value) || 0;
        });

        document.getElementById('totalDebit').textContent = '₹' + totalDebit.toFixed(2);
        document.getElementById('totalCredit').textContent = '₹' + totalCredit.toFixed(2);
        document.getElementById('summaryDebit').textContent = '₹' + totalDebit.toFixed(2);
        document.getElementById('summaryCredit').textContent = '₹' + totalCredit.toFixed(2);

        const diff = Math.abs(totalDebit - totalCredit);
        document.getElementById('summaryDiff').textContent = '₹' + diff.toFixed(2);

        const balanced = Math.abs(totalDebit - totalCredit) < 0.01 && totalDebit > 0;
        const statusEl = document.getElementById('balanceStatus');
        statusEl.textContent = balanced ? 'Balanced' : 'Unbalanced';
        statusEl.className = 'badge ' + (balanced ? 'bg-success' : 'bg-danger');
    }

    bindEvents();
});
</script>
@endpush
