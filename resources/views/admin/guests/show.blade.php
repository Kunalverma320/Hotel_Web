@extends('admin.layouts.app')
@section('title', $guest->full_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">{{ $guest->full_name }}</h4>
        <small class="text-muted">Guest Profile</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.guests.edit', $guest->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        @if($guest->is_blacklisted)
            <form method="POST" action="{{ route('admin.guests.blacklist', $guest->id) }}">
                @csrf @method('PATCH')
                <button class="btn btn-success btn-sm"><i class="bi bi-unlock me-1"></i> Remove Blacklist</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.guests.blacklist', $guest->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="reason" value="Manual blacklist">
                <button class="btn btn-danger btn-sm" onclick="return confirm('Blacklist this guest?')"><i class="bi bi-slash-circle me-1"></i> Blacklist</button>
            </form>
        @endif
        <a href="{{ route('admin.guests.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:2rem;">
                    {{ strtoupper(substr($guest->first_name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $guest->full_name }}</h5>
                <div class="text-muted mb-2">{{ $guest->email ?? 'No email' }}</div>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-{{ $guest->is_blacklisted ? 'danger' : 'success' }}">{{ $guest->is_blacklisted ? 'Blacklisted' : 'Active' }}</span>
                    <span class="badge bg-{{ ['silver'=>'secondary','gold'=>'warning','platinum'=>'info','diamond'=>'primary'][$guest->loyalty_tier] ?? 'light text-dark' }}">{{ ucfirst($guest->loyalty_tier ?? 'Standard') }}</span>
                </div>
                <div class="row text-center small">
                    <div class="col-4">
                        <div class="fw-bold fs-5 text-primary">{{ $totalStays }}</div>
                        <div class="text-muted">Stays</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-5 text-primary">${{ number_format($totalSpent, 0) }}</div>
                        <div class="text-muted">Spent</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-5 text-primary">{{ $guest->loyalty_points ?? 0 }}</div>
                        <div class="text-muted">Points</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Contact Information</h6></div>
            <div class="card-body">
                <div class="small">
                    <div class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $guest->email ?? 'N/A' }}</div>
                    <div class="mb-2"><i class="bi bi-phone me-2 text-muted"></i>{{ $guest->phone ?? 'N/A' }}</div>
                    @if($guest->secondary_phone)
                        <div class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $guest->secondary_phone }}</div>
                    @endif
                    <div class="mb-2"><i class="bi bi-building me-2 text-muted"></i>{{ $guest->company_name ?? 'N/A' }}</div>
                    <div class="mb-2"><i class="bi bi-globe me-2 text-muted"></i>{{ $guest->nationality ?? 'N/A' }}</div>
                    <div class="mb-2"><i class="bi bi-gender-ambiguous me-2 text-muted"></i>{{ ucfirst($guest->gender ?? 'N/A') }}</div>
                    <div class="mb-2"><i class="bi bi-calendar me-2 text-muted"></i>{{ $guest->date_of_birth ? $guest->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                    @if($guest->id_type)
                        <div class="mb-2"><i class="bi bi-card-heading me-2 text-muted"></i>{{ $guest->id_type }}: {{ $guest->id_number }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Address</h6></div>
            <div class="card-body small">
                {{ $guest->address ?? 'N/A' }}<br>
                {{ $guest->city ?? '' }}{{ $guest->city && $guest->state ? ', ' : '' }}{{ $guest->state ?? '' }}<br>
                {{ $guest->country ?? '' }} {{ $guest->postal_code ?? '' }}
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-bookings">Bookings</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-documents">Documents</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-preferences">Preferences</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-loyalty">Loyalty</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-notes">Notes</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-bookings">
                @if($guest->bookings->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead><tr><th>Booking #</th><th>Check-in</th><th>Check-out</th><th>Room</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($guest->bookings as $b)
                                    <tr>
                                        <td><a href="{{ route('admin.bookings.show', $b->id) }}" class="text-decoration-none">{{ $b->booking_number }}</a></td>
                                        <td>{{ $b->check_in_date->format('M d, Y') }}</td>
                                        <td>{{ $b->check_out_date->format('M d, Y') }}</td>
                                        <td>{{ $b->roomType->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($b->total_amount, 2) }}</td>
                                        <td><span class="badge bg-{{ ['pending'=>'warning','confirmed'=>'info','checked_in'=>'success','checked_out'=>'primary','cancelled'=>'danger'][$b->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$b->status)) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No bookings yet</p>
                @endif
            </div>

            <div class="tab-pane fade" id="tab-documents">
                @if($guest->documents->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Type</th><th>Number</th><th>Expiry</th><th>Country</th><th>Verified</th></tr></thead>
                            <tbody>
                                @foreach($guest->documents as $doc)
                                    <tr>
                                        <td>{{ $doc->document_type }}</td>
                                        <td>{{ $doc->document_number }}</td>
                                        <td>{{ $doc->expiry_date?->format('M d, Y') ?? '-' }}</td>
                                        <td>{{ $doc->issuing_country ?? '-' }}</td>
                                        <td><span class="badge bg-{{ $doc->is_verified ? 'success' : 'warning' }}">{{ $doc->is_verified ? 'Verified' : 'Pending' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No documents uploaded</p>
                @endif
            </div>

            <div class="tab-pane fade" id="tab-preferences">
                @if($guest->preferences->count())
                    <div class="list-group">
                        @foreach($guest->preferences as $pref)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary me-2">{{ ucfirst($pref->preference_type) }}</span>
                                        <strong>{{ $pref->preference_key }}</strong>
                                    </div>
                                    <span>{{ $pref->preference_value }}</span>
                                </div>
                                @if($pref->notes)<div class="small text-muted mt-1">{{ $pref->notes }}</div>@endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4">No preferences saved</p>
                @endif
            </div>

            <div class="tab-pane fade" id="tab-loyalty">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="fs-2 fw-bold text-primary">{{ $guest->loyalty_points ?? 0 }}</div>
                                <div class="text-muted">Available Points</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="fs-2 fw-bold text-success">{{ ucfirst($guest->loyalty_tier ?? 'Standard') }}</div>
                                <div class="text-muted">Current Tier</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="fs-2 fw-bold text-info">{{ $guest->loyaltyTransactions->where('type', 'earned')->sum('points') }}</div>
                                <div class="text-muted">Total Earned</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($guest->loyaltyTransactions->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Date</th><th>Type</th><th>Points</th><th>Balance</th><th>Description</th></tr></thead>
                            <tbody>
                                @foreach($guest->loyaltyTransactions as $tx)
                                    <tr>
                                        <td>{{ $tx->created_at->format('M d, Y') }}</td>
                                        <td><span class="badge bg-{{ $tx->type == 'earned' ? 'success' : ($tx->type == 'redeemed' ? 'warning' : 'danger') }}">{{ ucfirst($tx->type) }}</span></td>
                                        <td class="{{ $tx->type == 'earned' ? 'text-success' : 'text-danger' }}">{{ $tx->type == 'earned' ? '+' : '-' }}{{ $tx->points }}</td>
                                        <td>{{ $tx->balance_after }}</td>
                                        <td class="small text-muted">{{ $tx->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No loyalty transactions</p>
                @endif
            </div>

            <div class="tab-pane fade" id="tab-notes">
                <div class="mb-3">
                    <form method="POST" action="{{ route('admin.crm.add-note', $guest->id) }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="note" class="form-control" placeholder="Add a note..." required>
                            <select name="category" class="form-select" style="max-width:150px;">
                                <option value="general">General</option>
                                <option value="complaint">Complaint</option>
                                <option value="preference">Preference</option>
                                <option value="vip">VIP</option>
                            </select>
                            <button class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>
                </div>
                @if($guest->customerNotes->count())
                    @foreach($guest->customerNotes as $note)
                        <div class="card mb-2">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="badge bg-secondary me-1">{{ ucfirst($note->category ?? 'General') }}</span>
                                        @if($note->is_important)<span class="badge bg-danger me-1">Important</span>@endif
                                        <span class="small text-muted">{{ $note->user->name ?? 'System' }} - {{ $note->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="mt-1 small">{{ $note->note }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No notes</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
