@extends('admin.layouts.app')

@section('title', 'Gift Cards')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Gift Cards</h4>
    <a href="{{ route('admin.marketing.gift-card-create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> New Gift Card</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Recipient</th>
                    <th>Initial Balance</th>
                    <th>Remaining</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($giftCards as $card)
                    <tr>
                        <td><code class="fs-6">{{ $card->code }}</code></td>
                        <td>
                            {{ $card->recipient_name ?? '-' }}
                            @if($card->recipient_email)<br><small class="text-muted">{{ $card->recipient_email }}</small>@endif
                        </td>
                        <td>${{ number_format($card->initial_balance, 2) }}</td>
                        <td><strong class="text-success">${{ number_format($card->balance, 2) }}</strong></td>
                        <td>{{ $card->expires_at ? \Carbon\Carbon::parse($card->expires_at)->format('M d, Y') : 'Never' }}</td>
                        <td>
                            @if($card->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($card->status === 'used')
                                <span class="badge bg-secondary">Used</span>
                            @else
                                <span class="badge bg-danger">Expired</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info" onclick="navigator.clipboard.writeText('{{ $card->code }}')" title="Copy Code"><i class="bi bi-clipboard"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No gift cards found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $giftCards->links() }}</div>
@endsection
