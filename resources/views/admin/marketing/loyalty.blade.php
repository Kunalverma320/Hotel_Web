@extends('admin.layouts.app')

@section('title', 'Loyalty Programs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Loyalty Programs</h4>
    <a href="{{ route('admin.marketing.loyalty-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Program</a>
</div>

<div class="row g-4">
    @forelse($programs as $program)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $program->name }}</h5>
                        @if($program->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0">{{ $program->points_per_dollar }}</h4>
                                <small class="text-muted">Points/$</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">${{ number_format($program->redeem_rate, 2) }}</h4>
                                <small class="text-muted">Per Point</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h4 class="text-info mb-0">{{ $program->min_points_redeem }}</h4>
                                <small class="text-muted">Min Redeem</small>
                            </div>
                        </div>
                    </div>
                    @if($program->description)
                        <p class="text-muted small mb-0">{{ $program->description }}</p>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">Created {{ $program->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-award text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted">No loyalty programs found.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $programs->links() }}</div>
@endsection
