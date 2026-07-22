@extends('admin.layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Balance Sheet</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h5 class="fw-bold">Balance Sheet</h5>
            <p class="text-muted">As of {{ now()->format('d M Y') }}</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3"><i class="fas fa-landmark me-1"></i> Assets</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Account</th><th class="text-end">Balance (₹)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($assetAccounts as $account)
                            <tr>
                                <td>{{ $account->code }} - {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No asset accounts.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold table-primary">
                            <td>Total Assets</td>
                            <td class="text-end">₹{{ number_format($totalAssets, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-6">
                <h6 class="text-danger mb-3"><i class="fas fa-file-invoice-dollar me-1"></i> Liabilities</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Account</th><th class="text-end">Balance (₹)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($liabilityAccounts as $account)
                            <tr>
                                <td>{{ $account->code }} - {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No liability accounts.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold table-danger">
                            <td>Total Liabilities</td>
                            <td class="text-end">₹{{ number_format($totalLiabilities, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <h6 class="text-info mb-3 mt-4"><i class="fas fa-balance-scale me-1"></i> Equity</h6>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Account</th><th class="text-end">Balance (₹)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($equityAccounts as $account)
                            <tr>
                                <td>{{ $account->code }} - {{ $account->name }}</td>
                                <td class="text-end">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">No equity accounts.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold table-info">
                            <td>Total Equity</td>
                            <td class="text-end">₹{{ number_format($totalEquity, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr class="table-primary">
                        <td class="fw-bold">Total Assets</td>
                        <td class="text-end fw-bold">₹{{ number_format($totalAssets, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr class="{{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'table-success' : 'table-warning' }}">
                        <td class="fw-bold">Total Liabilities + Equity</td>
                        <td class="text-end fw-bold">₹{{ number_format($totalLiabilities + $totalEquity, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-primary, .table-danger, .table-info, .table-success, .table-warning {
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
        }
    }
</style>
@endpush
@endsection
