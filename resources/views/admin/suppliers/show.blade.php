@extends('admin.layouts.app')

@section('title', $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $supplier->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('admin.suppliers.ledger', $supplier->id) }}" class="btn btn-outline-info">
            <i class="fas fa-book me-1"></i> Ledger
        </a>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Supplier Information</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="140">Contact Person</th><td>{{ $supplier->contact_person ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $supplier->email ?? '-' }}</td></tr>
                    <tr><th>Phone</th><td>{{ $supplier->phone ?? '-' }}</td></tr>
                    <tr><th>Address</th><td>{{ $supplier->address ?? '-' }}</td></tr>
                    <tr><th>City</th><td>{{ $supplier->city ?? '-' }}, {{ $supplier->state ?? '' }} {{ $supplier->zip_code ?? '' }}</td></tr>
                    <tr><th>Country</th><td>{{ $supplier->country ?? '-' }}</td></tr>
                    <tr><th>Tax Number</th><td>{{ $supplier->tax_number ?? '-' }}</td></tr>
                    <tr><th>Payment Terms</th><td>{{ $supplier->payment_terms ?? '-' }} days</td></tr>
                    <tr><th>Bank Name</th><td>{{ $supplier->bank_name ?? '-' }}</td></tr>
                    <tr><th>Bank Account</th><td>{{ $supplier->bank_account ?? '-' }}</td></tr>
                    <tr><th>Status</th>
                        <td><span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($supplier->status) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h4 class="text-primary mb-0">₹{{ number_format($totalPurchases, 2) }}</h4>
                        <small class="text-muted">Total Purchases</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h4 class="text-success mb-0">₹{{ number_format($totalPaid, 2) }}</h4>
                        <small class="text-muted">Total Paid</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-{{ $balance > 0 ? 'danger' : 'success' }}">
                    <div class="card-body">
                        <h4 class="text-{{ $balance > 0 ? 'danger' : 'success' }} mb-0">₹{{ number_format($balance, 2) }}</h4>
                        <small class="text-muted">Balance Due</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Recent Payments</h6></div>
            <div class="card-body">
                @forelse($payments as $payment)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                            <br><small class="text-muted">{{ $payment->payment_method }} | {{ $payment->payment_date }}</small>
                        </div>
                        <span class="text-muted small">{{ $payment->reference ?? '-' }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">No payments recorded yet.</p>
                @endforelse
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('admin.suppliers.payments', $supplier->id) }}" class="text-decoration-none">
                    View All Payments <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Make Payment</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.suppliers.make-payment', $supplier->id) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference" class="form-control" placeholder="Transaction ID">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="1"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-money-check me-1"></i> Record Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
