@extends('admin.layouts.app')
@section('title', 'Customer Notes - ' . $guest->full_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Customer Notes</h4>
        <small class="text-muted">{{ $guest->full_name }}</small>
    </div>
    <a href="{{ route('admin.guests.show', $guest->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        @forelse($guest->customerNotes->sortByDesc('created_at') as $note)
            <div class="card mb-3 {{ $note->is_important ? 'border-danger' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-secondary me-1">{{ ucfirst($note->category ?? 'General') }}</span>
                            @if($note->is_important)<span class="badge bg-danger">Important</span>@endif
                        </div>
                        <div class="small text-muted">{{ $note->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="mt-2">{{ $note->note }}</div>
                    <div class="mt-2 small text-muted">
                        <i class="bi bi-person me-1"></i> {{ $note->user->name ?? 'System' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-text" style="font-size:3rem;"></i>
                <p class="mt-2">No notes yet</p>
            </div>
        @endforelse
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Add Note</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.crm.add-note', $guest->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="general">General</option>
                            <option value="complaint">Complaint</option>
                            <option value="preference">Preference</option>
                            <option value="vip">VIP</option>
                            <option value="feedback">Feedback</option>
                            <option value="internal">Internal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control" rows="4" required placeholder="Write your note..."></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_important" value="1" class="form-check-input" id="isImportant">
                            <label class="form-check-label" for="isImportant">Mark as Important</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i> Add Note</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
