@extends('admin.layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Trial Balance</h4>
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
            <h5 class="fw-bold">Trial Balance</h5>
            <p class="text-muted">As of {{ now()->format('d M Y') }}</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm" id="printArea">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th class="text-end">Debit (₹)</th>
                        <th class="text-end">Credit (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentType = null;
                    @endphp
                    @forelse($accounts as $account)
                        @if($currentType !== $account->type)
                            @php $currentType = $account->type; @endphp
                            <tr class="table-light">
                                <td colspan="5"><strong>{{ ucfirst($currentType) }} Accounts</strong></td>
                            </tr>
                        @endif
                        <tr>
                            <td><code>{{ $account->code }}</code></td>
                            <td>{{ $account->name }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($account->type) }}</span></td>
                            <td class="text-end">
                                @if($account->total_debit > 0)
                                    {{ number_format($account->total_debit, 2) }}
                                @else - @endif
                            </td>
                            <td class="text-end">
                                @if($account->total_credit > 0)
                                    {{ number_format($account->total_credit, 2) }}
                                @else - @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No account data found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-dark text-white fw-bold">
                        <td colspan="3" class="text-end">TOTAL</td>
                        <td class="text-end">₹{{ number_format($totalDebit, 2) }}</td>
                        <td class="text-end">₹{{ number_format($totalCredit, 2) }}</td>
                    </tr>
                    <tr class="{{ abs($totalDebit - $totalCredit) < 0.01 ? 'table-success' : 'table-danger' }}">
                        <td colspan="3" class="text-end fw-bold">Difference</td>
                        <td colspan="2" class="text-end fw-bold">
                            ₹{{ number_format(abs($totalDebit - $totalCredit), 2) }}
                            {{ abs($totalDebit - $totalCredit) < 0.01 ? '(Balanced)' : '(Unbalanced!)' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 11px; }
        .table-dark { background-color: #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endpush
@endsection
