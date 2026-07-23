@extends('layouts.guest')

@section('title', 'My Bookings | MakeMyTrip Hotels')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header section -->
            <div class="d-flex justify-content-between align-items-center mb-4 text-start">
                <div>
                    <h3 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif; color: var(--text);">My Bookings</h3>
                    <p class="text-muted mb-0 small">Manage your current and past hotel bookings.</p>
                </div>
                <a href="{{ url('/') }}" class="btn btn-sm btn-primary py-2 px-3 fw-bold" style="border-radius: 10px; background: linear-gradient(90deg, #60b4ff 0%, #008cff 100%); border: none;">
                    <i class="bi bi-search me-1"></i>Book Another Hotel
                </a>
            </div>

            <!-- Empty state -->
            @if($bookings->isEmpty())
                <div class="card border-0 shadow-sm text-center py-5 px-4" style="border-radius: 20px; background: var(--card-bg);">
                    <div class="d-inline-flex p-4 bg-primary-light text-primary rounded-circle mb-4">
                        <i class="bi bi-journal-x" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold">No Bookings Found</h4>
                    <p class="text-muted mx-auto" style="max-width: 400px;">It looks like you haven't made any reservations yet. Search and book hotels in top destinations now!</p>
                    <a href="{{ url('/') }}" class="btn btn-primary py-3 px-5 mt-3 fw-bold" style="border-radius: 12px; background: linear-gradient(90deg, #60b4ff 0%, #008cff 100%); border: none; box-shadow: 0 4px 12px rgba(0, 140, 255, 0.25);">
                        Explore Hotels
                    </a>
                </div>
            @else
                <!-- Bookings listing -->
                <div class="d-flex flex-column gap-3 text-start">
                    @foreach($bookings as $booking)
                        @php
                            $statusClasses = [
                                'pending' => 'bg-warning text-dark border-warning',
                                'confirmed' => 'bg-success-subtle text-success border-success',
                                'checked_in' => 'bg-info-subtle text-info border-info',
                                'checked_out' => 'bg-secondary-subtle text-secondary border-secondary',
                                'cancelled' => 'bg-danger-subtle text-danger border-danger',
                                'no_show' => 'bg-dark-subtle text-dark border-dark',
                            ];
                            
                            // Mock hotel images
                            $hotelImages = [
                                'grand-luxury-hotel-nyc' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=400&q=80',
                                'seaside-resort-miami' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=400&q=80',
                                'mountain-lodge-denver' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=400&q=80',
                            ];
                            $hotelCover = $hotelImages[$booking->hotel->slug] ?? 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=400&q=80';
                        @endphp
                        
                        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px; background: var(--card-bg); border: 1px solid var(--border) !important;">
                            <div class="row g-0">
                                <!-- Hotel Image Thumbnail -->
                                <div class="col-md-3">
                                    <div class="h-100 position-relative" style="min-height: 160px; background: url('{{ $hotelCover }}') center/cover no-repeat;">
                                    </div>
                                </div>
                                
                                <!-- Booking Details -->
                                <div class="col-md-9 p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-primary">{{ $booking->hotel->name }}</h5>
                                                <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill me-1"></i>{{ $booking->hotel->city }}, {{ $booking->hotel->country }}</p>
                                            </div>
                                            <div class="text-md-end">
                                                <span class="badge border py-2 px-3 {{ $statusClasses[$booking->status] ?? 'bg-secondary' }}" style="font-size: 0.85rem; border-radius: 8px;">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <hr class="my-3" style="opacity: 0.1;">
                                        
                                        <div class="row g-3">
                                            <div class="col-6 col-sm-3">
                                                <span class="text-muted d-block small text-uppercase fw-bold">Booking ID</span>
                                                <span class="fw-bold text-dark">{{ $booking->booking_number }}</span>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <span class="text-muted d-block small text-uppercase fw-bold">Room Type</span>
                                                <span class="fw-semibold text-dark">{{ $booking->roomType->name }}</span>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <span class="text-muted d-block small text-uppercase fw-bold">Check-in</span>
                                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <span class="text-muted d-block small text-uppercase fw-bold">Check-out</span>
                                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3 border-top" style="border-top-color: rgba(255,255,255,0.05) !important;">
                                        <div class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>Standard check-in: 12:00 PM
                                        </div>
                                        <div class="text-end">
                                            <span class="text-muted small me-2">Total Price (incl. tax)</span>
                                            <span class="fs-4 fw-bold text-primary">${{ number_format($booking->net_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
