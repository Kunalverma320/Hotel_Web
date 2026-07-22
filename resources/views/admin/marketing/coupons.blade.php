@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Coupons</h4>
    <a href="{{ route('admin.marketing.coupon-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Coupon</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><code class="fs-6">{{ $coupon->code }}</code></td>
                        <td><span class="badge bg-info-subtle text-info">{{ ucfirst($coupon->type) }}</span></td>
                        <td><strong>{{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}</strong></td>
                        <td>{{ $coupon->min_order ? '$' . number_format($coupon->min_order, 2) : '-' }}</td>
                        <td>{{ $coupon->used_count ?? 0 }}{{ $coupon->max_uses ? ' / ' . $coupon->max_uses : '' }}</td>
                        <td>
                            <small>{{ \Carbon\Carbon::parse($coupon->starts_at)->format('M d') }} - {{ \Carbon\Carbon::parse($coupon->ends_at)->format('M d, Y') }}</small>
                        </td>
                        <td>
                            @if($coupon->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.marketing.coupon-edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.marketing.coupon-destroy', $coupon->id) }}" class="d-inline" onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No coupons found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $coupons->links() }}</div>
@endsection
