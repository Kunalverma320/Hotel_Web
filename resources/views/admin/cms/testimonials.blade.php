@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Testimonials</h4>
    <a href="{{ route('admin.cms.testimonial-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Testimonial</a>
</div>

<div class="row g-3">
    @forelse($testimonials as $testimonial)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                            {{ strtoupper(substr($testimonial->customer_name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $testimonial->customer_name }}</h6>
                            <small class="text-muted">{{ $testimonial->customer_email ?? '' }}</small>
                        </div>
                        <div class="ms-auto">
                            @if($testimonial->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($testimonial->rating)
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                    @endif
                    <p class="card-text">"{{ $testimonial->content }}"</p>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.cms.testimonial-update', $testimonial->id) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="customer_name" value="{{ $testimonial->customer_name }}">
                            <input type="hidden" name="content" value="{{ $testimonial->content }}">
                            <input type="hidden" name="customer_email" value="{{ $testimonial->customer_email }}">
                            <input type="hidden" name="rating" value="{{ $testimonial->rating }}">
                            <input type="hidden" name="status" value="{{ $testimonial->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="btn btn-sm btn-outline-{{ $testimonial->status === 'active' ? 'warning' : 'success' }}">
                                {{ $testimonial->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.cms.testimonial-destroy', $testimonial->id) }}" class="ms-auto" onsubmit="return confirm('Delete this testimonial?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-chat-quote text-muted" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted">No testimonials found.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $testimonials->links() }}</div>
@endsection
