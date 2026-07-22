@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">FAQs</h4>
    <a href="{{ route('admin.cms.faq-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New FAQ</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div id="faqList">
            @forelse($faqs as $faq)
                <div class="faq-item d-flex align-items-start border-bottom p-3" data-id="{{ $faq->id }}">
                    <div class="handle me-3 text-muted" style="cursor:grab; font-size:1.2rem;" title="Drag to reorder">
                        <i class="bi bi-grip-vertical"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <h6 class="mb-0 me-2">{{ $faq->question }}</h6>
                            @if($faq->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            <span class="badge bg-light text-dark ms-1">#{{ $faq->sort_order }}</span>
                        </div>
                        <p class="mb-0 text-muted small">{{ Str::limit($faq->answer, 150) }}</p>
                    </div>
                    <div class="d-flex gap-2 ms-3">
                        <a href="{{ route('admin.cms.faq-edit', $faq->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.cms.faq-destroy', $faq->id) }}" class="d-inline" onsubmit="return confirm('Delete this FAQ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-question-circle text-muted" style="font-size: 4rem;"></i>
                    <p class="mt-3 text-muted">No FAQs found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4">{{ $faqs->links() }}</div>
@endsection
