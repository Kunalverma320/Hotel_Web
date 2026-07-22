@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Chart of Accounts</h4>
    <a href="{{ route('admin.finance.coa-create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Account
    </a>
</div>

<div class="card">
    <div class="card-body">
        @php
            $grouped = $accounts->groupBy('type');
            $typeIcons = [
                'asset' => 'fas fa-landmark',
                'liability' => 'fas fa-file-invoice-dollar',
                'equity' => 'fas fa-balance-scale',
                'income' => 'fas fa-arrow-up',
                'expense' => 'fas fa-arrow-down',
            ];
            $typeColors = [
                'asset' => 'primary',
                'liability' => 'danger',
                'equity' => 'info',
                'income' => 'success',
                'expense' => 'warning',
            ];
        @endphp

        @foreach($grouped as $type => $typeAccounts)
            <div class="mb-4">
                <h5 class="text-{{ $typeColors[$type] ?? 'secondary' }}">
                    <i class="{{ $typeIcons[$type] ?? 'fas fa-tag' }} me-2"></i>
                    {{ ucfirst($type) }} Accounts
                    <span class="badge bg-{{ $typeColors[$type] ?? 'secondary' }} ms-2">{{ $typeAccounts->count() }}</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-{{ $typeColors[$type] ?? 'secondary' }}">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Parent</th>
                                <th>Group</th>
                                <th>Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($typeAccounts as $account)
                                <tr>
                                    <td><code>{{ $account->code }}</code></td>
                                    <td>
                                        <a href="{{ route('admin.finance.ledger', $account->id) }}">
                                            {{ $account->name }}
                                        </a>
                                    </td>
                                    <td>{{ $account->description ?? '-' }}</td>
                                    <td>{{ $account->parent->name ?? '-' }}</td>
                                    <td>
                                        @if($account->is_group)
                                            <span class="badge bg-secondary">Group</span>
                                        @else
                                            <span class="badge bg-light text-dark">Detail</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $account->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($account->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.finance.ledger', $account->id) }}" class="btn btn-outline-info" title="Ledger">
                                                <i class="fas fa-book"></i>
                                            </a>
                                            <a href="{{ route('admin.finance.coa-create') }}" class="btn btn-outline-primary" title="Add Sub-account">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                @if($account->children->count())
                                    @foreach($account->children as $child)
                                        <tr class="table-light">
                                            <td>&nbsp;&nbsp;&nbsp;<code>{{ $child->code }}</code></td>
                                            <td>&nbsp;&nbsp;&nbsp;|-- {{ $child->name }}</td>
                                            <td>{{ $child->description ?? '-' }}</td>
                                            <td>{{ $account->name }}</td>
                                            <td><span class="badge bg-light text-dark">Detail</span></td>
                                            <td>
                                                <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($child->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.finance.ledger', $child->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-book"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No accounts in this category.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
