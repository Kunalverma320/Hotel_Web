@extends('admin.layouts.app')
@section('title', 'Add Lead')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Add Lead</h4>
        <small class="text-muted">Create a new sales lead</small>
    </div>
    <a href="{{ route('admin.crm.leads') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.crm.lead-store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Hotel <span class="text-danger">*</span></label>
                    <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
                        <option value="">Select Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-select">
                        <option value="">Select</option>
                        @foreach(['website','phone','walk_in','referral','travel_agent','corporate','social_media','other'] as $s)
                            <option value="{{ $s }}" {{ old('source') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['new','contacted','qualified','proposal','negotiation'] as $s)
                            <option value="{{ $s }}" {{ old('status', 'new') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" {{ old('priority', 'medium') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Preferred Check-in</label>
                    <input type="date" name="check_in_date" class="form-control" value="{{ old('check_in_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Preferred Check-out</label>
                    <input type="date" name="check_out_date" class="form-control" value="{{ old('check_out_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Number of Guests</label>
                    <input type="number" name="guests" class="form-control" value="{{ old('guests') }}" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Budget</label>
                    <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget') }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Room Preference</label>
                    <input type="text" name="room_preference" class="form-control" value="{{ old('room_preference') }}" placeholder="e.g. Deluxe Suite">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned To</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Next Follow-up</label>
                    <input type="date" name="next_followup_at" class="form-control" value="{{ old('next_followup_at') }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Lead</button>
                <a href="{{ route('admin.crm.leads') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
