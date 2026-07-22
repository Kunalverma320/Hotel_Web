@extends('admin.layouts.app')

@section('title', 'Assign Housekeeper')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Assign Housekeeper</h4>
    <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.housekeeping.assign') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Task ID</label>
                    <input type="number" name="task_id" class="form-control" value="{{ request('task_id') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Select Housekeeper</label>
                    <select name="assigned_to" class="form-select" required>
                        <option value="">-- Select Staff --</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->position ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Add any special instructions..."></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Assign</button>
                <a href="{{ route('admin.housekeeping.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
