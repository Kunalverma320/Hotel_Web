@extends('layouts.guest')

@section('title', 'MakeMyTrip Hotels | Book Hotels, Resorts & Homestays')

@section('styles')
<style>
    /* MakeMyTrip Product Tabs */
    .tabs-container {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: center;
        gap: 20px;
        max-width: 820px;
        margin: -32px auto 0 auto;
        position: relative;
        z-index: 101;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .tab-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.5rem 1rem;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
        border-bottom: 3px solid transparent;
        cursor: pointer;
    }

    .tab-item i {
        font-size: 1.5rem;
        margin-bottom: 2px;
        color: var(--text-muted);
        transition: var(--transition);
    }

    .tab-item:hover, .tab-item.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-item:hover i, .tab-item.active i {
        color: var(--primary);
        transform: translateY(-2px);
    }

    /* Hero Banner */
    .hero-banner {
        background: var(--header-gradient);
        height: 180px;
        width: 100%;
    }

    /* MakeMyTrip Search Widget */
    .search-widget-container {
        max-width: 1100px;
        margin: -80px auto 4rem auto;
        padding: 0 15px;
        position: relative;
        z-index: 102;
    }

    .search-widget-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        padding: 2rem;
        position: relative;
    }

    .search-row {
        display: grid;
        grid-template-columns: 2.2fr 1.3fr 1.3fr 1.7fr;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        background: var(--card-bg);
    }

    .search-col {
        padding: 1.25rem 1.5rem;
        border-right: 1px solid var(--border);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .search-col:last-child {
        border-right: none;
    }

    .search-col:hover {
        background: var(--primary-light);
    }

    .search-col-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .search-col-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text);
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        padding: 0;
        cursor: pointer;
    }

    .search-col-value::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }

    .search-col-sub {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .search-submit-btn {
        background: var(--search-btn-gradient);
        border: none;
        color: #fff;
        font-weight: 800;
        font-size: 1.25rem;
        border-radius: 50px;
        padding: 0.9rem 4rem;
        position: absolute;
        bottom: -28px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 10px 25px rgba(6, 90, 243, 0.45);
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-submit-btn:hover {
        transform: translateX(-50%) translateY(-2px);
        box-shadow: 0 12px 30px rgba(6, 90, 243, 0.6);
        color: #fff;
    }

    /* Hotels List Area */
    .list-section-header {
        max-width: 1100px;
        margin: 0 auto 1.5rem auto;
        padding: 0 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-section-header h2 {
        font-weight: 800;
        font-size: 1.75rem;
        color: var(--text);
    }

    .hotels-list-container {
        max-width: 1100px;
        margin: 0 auto 5rem auto;
        padding: 0 15px;
    }

    /* MMT Styled Hotel Card */
    .mmt-hotel-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .mmt-hotel-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        border-color: rgba(0, 140, 255, 0.25);
    }

    .card-row {
        display: flex;
        flex-wrap: wrap;
    }

    .card-img-col {
        flex: 0 0 280px;
        height: 250px;
        position: relative;
        overflow: hidden;
    }

    .card-img-col img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .mmt-hotel-card:hover .card-img-col img {
        transform: scale(1.05);
    }

    .card-info-col {
        flex: 1;
        padding: 1.75rem 2rem;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--border);
    }

    .card-price-col {
        flex: 0 0 240px;
        padding: 1.75rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
        background: rgba(0, 140, 255, 0.01);
    }

    .hotel-rating-stars {
        display: flex;
        gap: 2px;
        color: var(--accent);
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .hotel-title-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
    }

    .hotel-tagline-text {
        font-style: italic;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .hotel-location-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hotel-amenities-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: auto;
    }

    .hotel-amenity-badge {
        background: var(--bg-light);
        color: var(--text-muted);
        padding: 0.35rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--border);
    }

    .price-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
    }

    .price-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1.1;
    }

    .price-value span {
        font-size: 0.9rem;
        font-weight: 400;
        color: var(--text-muted);
    }

    .price-sub {
        font-size: 0.75rem;
        color: #22c55e;
        font-weight: 600;
        margin-top: 4px;
        margin-bottom: 20px;
    }

    .book-btn {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.95rem;
        width: 100%;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(0, 140, 255, 0.2);
    }

    .book-btn:hover {
        background: var(--primary-hover);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(0, 140, 255, 0.35);
    }

    /* Booking Drawer Panel */
    .drawer-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 1050;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .booking-drawer {
        position: fixed;
        top: 0; right: -500px; bottom: 0;
        width: 100%;
        max-width: 480px;
        background: var(--card-bg);
        box-shadow: -10px 0 40px rgba(0,0,0,0.15);
        z-index: 1060;
        display: flex;
        flex-direction: column;
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .drawer-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .drawer-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex-grow: 1;
    }

    .form-control-mmt {
        border-radius: 10px;
        border: 1px solid var(--border);
        padding: 0.75rem 1rem;
        font-weight: 500;
        background: var(--bg-light);
        color: var(--text);
    }

    .form-control-mmt:focus {
        background: var(--card-bg);
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .drawer-success {
        text-align: center;
        padding: 3rem 1.5rem;
        display: none;
    }

    .empty-state {
        background: var(--card-bg);
        border: 1px dashed var(--border);
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    @media (max-width: 991px) {
        .search-row {
            grid-template-columns: 1fr;
        }
        .search-col {
            border-right: none;
            border-bottom: 1px solid var(--border);
        }
        .card-img-col {
            flex: 0 0 100%;
            height: 200px;
        }
        .card-price-col {
            flex: 0 0 100%;
            text-align: left;
            border-top: 1px solid var(--border);
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero Blue Banner background -->
    <section class="hero-banner"></section>

    <!-- Product Category Tabs -->
    <div class="tabs-container">
        <a class="tab-item active">
            <i class="bi bi-building"></i>
            <span>Hotels</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-airplane"></i>
            <span>Flights</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-house-door"></i>
            <span>Homestays</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-tags"></i>
            <span>Holiday Packages</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-train-front"></i>
            <span>Trains</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-bus-front"></i>
            <span>Buses</span>
        </a>
        <a class="tab-item">
            <i class="bi bi-taxi"></i>
            <span>Cabs</span>
        </a>
    </div>

    <!-- Search Engine Card -->
    <div class="search-widget-container">
        <div class="search-widget-card">
            <div class="search-row">
                <!-- Location City Dropdown -->
                <div class="search-col" onclick="document.getElementById('citySelect').focus()">
                    <div class="search-col-label">
                        <i class="bi bi-geo-alt"></i> City, Area or Property
                    </div>
                    <select class="search-col-value text-capitalize" id="citySelect" onchange="updateSearchSub()">
                        <option value="all">All Cities</option>
                        <option value="New York">New York, USA</option>
                        <option value="Miami Beach">Miami Beach, USA</option>
                        <option value="Denver">Denver, USA</option>
                    </select>
                    <div class="search-col-sub" id="citySub">USA Properties</div>
                </div>
                
                <!-- Check-in Date -->
                <div class="search-col" onclick="document.getElementById('checkinInput').showPicker()">
                    <div class="search-col-label">
                        <i class="bi bi-calendar3"></i> Check-In Date
                    </div>
                    <input type="date" class="search-col-value fw-bold" id="checkinInput" onchange="updateCheckinSub()">
                    <div class="search-col-sub" id="checkinSub">Set Date</div>
                </div>

                <!-- Check-out Date -->
                <div class="search-col" onclick="document.getElementById('checkoutInput').showPicker()">
                    <div class="search-col-label">
                        <i class="bi bi-calendar3"></i> Check-Out Date
                    </div>
                    <input type="date" class="search-col-value fw-bold" id="checkoutInput" onchange="updateCheckoutSub()">
                    <div class="search-col-sub" id="checkoutSub">Set Date</div>
                </div>

                <!-- Rooms & Guest count -->
                <div class="search-col">
                    <div class="search-col-label">
                        <i class="bi bi-people"></i> Guests & Rooms
                    </div>
                    <select class="search-col-value" id="guestSelect" onchange="updateGuestsSub()">
                        <option value="1">1 Room, 1 Adult</option>
                        <option value="2" selected>1 Room, 2 Adults</option>
                        <option value="3">1 Room, 3 Adults</option>
                        <option value="4">2 Rooms, 4 Adults</option>
                    </select>
                    <div class="search-col-sub" id="guestsSub">2 Guests</div>
                </div>
            </div>

            <!-- Submit Orange Search Button -->
            <button class="search-submit-btn" onclick="executeSearch()">Search</button>
        </div>
    </div>

    <!-- Listings Header -->
    <div class="list-section-header">
        <h2 id="listingsHeaderTitle">Popular Hotels</h2>
        <span class="text-muted fw-bold small" id="hotelsCount">Showing 3 Properties</span>
    </div>

    @php
    function getAmenityIcon($icon) {
        return match(strtolower($icon)) {
            'wifi', 'free wifi' => 'bi-wifi',
            'pool', 'swimming pool' => 'bi-droplet-half',
            'fitness', 'fitness center', 'gym' => 'bi-heart-pulse-fill',
            'restaurant' => 'bi-egg-fried',
            'room-service', 'room service' => 'bi-bell-fill',
            'parking' => 'bi-p-circle-fill',
            'shuttle', 'airport shuttle' => 'bi-bus-front',
            'spa' => 'bi-flower1',
            'business', 'business center' => 'bi-briefcase-fill',
            'laundry' => 'bi-droplet-half',
            'ac', 'air conditioning' => 'bi-wind',
            'minibar', 'mini bar' => 'bi-cup-hot-fill',
            'tv', 'flat screen tv' => 'bi-tv-fill',
            'safe', 'in-room safe' => 'bi-key-fill',
            'coffee', 'coffee maker' => 'bi-cup-hot',
            default => 'bi-check-circle-fill',
        };
    }

    function getHotelImage($slug) {
        return match($slug) {
            'grand-luxury-hotel-nyc' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
            'seaside-resort-miami' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80',
            'mountain-lodge-denver' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
            default => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
        };
    }
    @endphp

    <!-- Hotel listings matching search parameters -->
    <div class="hotels-list-container" id="hotelsList">
        @forelse($hotels as $hotel)
            @php
                $lowestPrice = $hotel->roomTypes->min('base_rate') ?? 150;
            @endphp
            <div class="mmt-hotel-card hotel-list-card" data-city="{{ $hotel->city }}" data-name="{{ strtolower($hotel->name) }}">
                <div class="card-row">
                    <!-- Hotel Image -->
                    <div class="card-img-col">
                        <img src="{{ getHotelImage($hotel->slug) }}" alt="{{ $hotel->name }}">
                    </div>
                    
                    <!-- Hotel info -->
                    <div class="card-info-col">
                        <div class="hotel-rating-stars">
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <h3 class="hotel-title-text">{{ $hotel->name }}</h3>
                        @if($hotel->tagline)
                            <p class="hotel-tagline-text">"{{ $hotel->tagline }}"</p>
                        @endif
                        
                        <div class="hotel-location-text">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            <span>{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->state }}, {{ $hotel->country }}</span>
                        </div>

                        <!-- Amenities -->
                        <div class="hotel-amenities-row">
                            @foreach($hotel->amenities->take(4) as $amenity)
                                <span class="hotel-amenity-badge">
                                    <i class="{{ getAmenityIcon($amenity->icon) }} text-primary"></i>
                                    {{ $amenity->name }}
                                </span>
                            @endforeach
                            @if($hotel->amenities->count() > 4)
                                <span class="hotel-amenity-badge bg-white">+{{ $hotel->amenities->count() - 4 }} More</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Hotel pricing and booking CTA -->
                    <div class="card-price-col">
                        <span class="price-label">Starting From</span>
                        <div class="price-value">${{ number_format($lowestPrice, 0) }}<span>/night</span></div>
                        <span class="price-sub">+ ${{ number_format($lowestPrice * 0.12, 0) }} taxes & fees</span>
                        
                        <button class="book-btn" 
                                onclick="openBookingDrawer('{{ $hotel->name }}', '{{ $hotel->email }}', '{{ $hotel->phone }}', '{{ json_encode($hotel->roomTypes->pluck('name', 'base_rate')->toArray()) }}')">
                            View Rooms
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-building-fill-exclamation" style="font-size:3rem;"></i>
                <h4>No Hotels Available</h4>
                <p class="text-muted">No hotel listings found in the database. Please run migrations/seeders.</p>
            </div>
        @endforelse
    </div>

    <!-- Booking Overlay Panel/Drawer -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeBookingDrawer()"></div>
    <div class="booking-drawer" id="bookingDrawer">
        <div class="drawer-header">
            <h5 class="fw-bold mb-0 text-primary" id="drawerHotelTitle">Hotel Name</h5>
            <button type="button" class="btn-close" onclick="closeBookingDrawer()"></button>
        </div>
        
        <div class="drawer-body">
            <!-- Hotel Contact Info (Always Visible) -->
            <div class="mb-4 text-center p-3 rounded-4" style="background: var(--bg-light); border: 1px solid var(--border);">
                <div class="fw-semibold text-dark"><i class="bi bi-telephone-fill me-2 text-primary"></i><span id="drawerHotelPhone">+1</span></div>
                <div class="fw-semibold text-dark mt-1"><i class="bi bi-envelope-fill me-2 text-primary"></i><span id="drawerHotelEmail">res@hotel.com</span></div>
            </div>

            @auth
            <!-- Form Details -->
            <form id="drawerBookingForm" onsubmit="submitDrawerInquiry(event)">

                <div class="mb-3">
                    <label for="drawerRoomType" class="form-label fw-bold small text-uppercase text-muted">Select Room Type</label>
                    <select class="form-select form-control-mmt" id="drawerRoomType" required>
                        <!-- Loaded dynamically -->
                    </select>
                </div>

                <div class="mb-3">
                    <label for="drawerGuestName" class="form-label fw-bold small text-uppercase text-muted">Your Full Name</label>
                    <input type="text" class="form-control form-control-mmt" id="drawerGuestName" required value="{{ auth()->user()->name ?? '' }}" placeholder="e.g. Jane Doe">
                </div>

                <div class="mb-3">
                    <label for="drawerGuestEmail" class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                    <input type="email" class="form-control form-control-mmt" id="drawerGuestEmail" required value="{{ auth()->user()->email ?? '' }}" placeholder="e.g. jane@example.com">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="drawerCheckin" class="form-label fw-bold small text-uppercase text-muted">Check-in</label>
                        <input type="date" class="form-control form-control-mmt" id="drawerCheckin" required>
                    </div>
                    <div class="col-6">
                        <label for="drawerCheckout" class="form-label fw-bold small text-uppercase text-muted">Check-out</label>
                        <input type="date" class="form-control form-control-mmt" id="drawerCheckout" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="drawerMessage" class="form-label fw-bold small text-uppercase text-muted">Special Requests</label>
                    <textarea class="form-control form-control-mmt" id="drawerMessage" rows="3" placeholder="Dietary needs, bed size, high floors..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius: 12px; font-weight: 700;">
                    <i class="bi bi-check-circle-fill me-2"></i>Send Booking Request
                </button>
            </form>

            <!-- Success Section -->
            <div class="drawer-success" id="drawerSuccessPanel">
                <div class="d-inline-flex p-3 bg-success-subtle text-success rounded-circle mb-3">
                    <i class="bi bi-check-circle-fill" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold text-dark">Booking Request Sent!</h4>
                <p class="text-muted">Your inquiry has been logged. Our hotel receptionist will contact you via email shortly.</p>
                <button type="button" class="btn btn-primary w-100 mt-3" style="border-radius: 12px; font-weight: 700;" onclick="closeBookingDrawer()">Done</button>
            </div>
            @else
            <!-- Request Login Panel -->
            <div class="text-center py-5 px-3">
                <div class="d-inline-flex p-3 bg-primary-light text-primary rounded-circle mb-4">
                    <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold text-dark">Sign In Required</h4>
                <p class="text-muted">To secure your reservation details and check room availability, please log in to your account first.</p>
                <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3 mt-3 fw-bold" style="border-radius: 12px;">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Continue
                </a>
            </div>
            @endauth
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Init check-in / check-out dates
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);

        document.getElementById('checkinInput').value = today.toISOString().split('T')[0];
        document.getElementById('checkoutInput').value = tomorrow.toISOString().split('T')[0];

        updateCheckinSub();
        updateCheckoutSub();
    });

    // Subtitle labels updates
    function updateSearchSub() {
        const val = document.getElementById('citySelect').value;
        const sub = document.getElementById('citySub');
        if (val === 'all') sub.textContent = 'USA Properties';
        else sub.textContent = val + ', USA';
    }

    function updateCheckinSub() {
        const val = document.getElementById('checkinInput').value;
        if (val) {
            const date = new Date(val);
            document.getElementById('checkinSub').textContent = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        }
    }

    function updateCheckoutSub() {
        const val = document.getElementById('checkoutInput').value;
        if (val) {
            const date = new Date(val);
            document.getElementById('checkoutSub').textContent = date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        }
    }

    function updateGuestsSub() {
        const select = document.getElementById('guestSelect');
        const text = select.options[select.selectedIndex].text;
        document.getElementById('guestsSub').textContent = text.split(',')[1] || text;
    }

    // Search engine execution
    function executeSearch() {
        const city = document.getElementById('citySelect').value;
        const cards = document.querySelectorAll('.hotel-list-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const hotelCity = card.getAttribute('data-city');
            if (city === 'all' || hotelCity.toLowerCase() === city.toLowerCase()) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('listingsHeaderTitle').textContent = city === 'all' ? 'Popular Hotels' : 'Hotels in ' + city;
        document.getElementById('hotelsCount').textContent = 'Showing ' + visibleCount + ' Properties';
    }

    // Booking Drawer logic
    function openBookingDrawer(name, email, phone, roomTypesJson) {
        document.getElementById('drawerHotelTitle').textContent = name;
        
        const phoneEl = document.getElementById('drawerHotelPhone');
        const emailEl = document.getElementById('drawerHotelEmail');
        if (phoneEl) phoneEl.textContent = phone;
        if (emailEl) emailEl.textContent = email;
        
        const roomTypeSelect = document.getElementById('drawerRoomType');
        if (roomTypeSelect) {
            roomTypeSelect.innerHTML = '';
            const roomTypes = JSON.parse(roomTypesJson);
            for (const [rate, roomName] of Object.entries(roomTypes)) {
                const opt = document.createElement('option');
                opt.value = roomName;
                opt.textContent = roomName + ' - $' + parseInt(rate) + '/night';
                roomTypeSelect.appendChild(opt);
            }
        }

        // Sync dates
        const checkinEl = document.getElementById('drawerCheckin');
        const checkoutEl = document.getElementById('drawerCheckout');
        if (checkinEl) checkinEl.value = document.getElementById('checkinInput').value;
        if (checkoutEl) checkoutEl.value = document.getElementById('checkoutInput').value;

        // Reset panels
        const formEl = document.getElementById('drawerBookingForm');
        const successEl = document.getElementById('drawerSuccessPanel');
        if (formEl) formEl.style.display = 'block';
        if (successEl) successEl.style.display = 'none';

        // Show drawer
        document.getElementById('drawerOverlay').style.display = 'block';
        setTimeout(() => {
            document.getElementById('drawerOverlay').style.opacity = '1';
            document.getElementById('bookingDrawer').style.right = '0';
        }, 50);
    }

    function closeBookingDrawer() {
        document.getElementById('bookingDrawer').style.right = '-500px';
        document.getElementById('drawerOverlay').style.opacity = '0';
        setTimeout(() => {
            document.getElementById('drawerOverlay').style.display = 'none';
        }, 300);
    }

    function submitDrawerInquiry(e) {
        e.preventDefault();
        
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        const data = {
            hotel_name: document.getElementById('drawerHotelTitle').textContent,
            room_type: document.getElementById('drawerRoomType').value,
            guest_name: document.getElementById('drawerGuestName').value,
            guest_email: document.getElementById('drawerGuestEmail').value,
            check_in: document.getElementById('drawerCheckin').value,
            check_out: document.getElementById('drawerCheckout').value,
            notes: document.getElementById('drawerMessage').value,
        };

        fetch('{{ route("guest-bookings.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            
            if (res.success) {
                document.getElementById('drawerBookingForm').style.display = 'none';
                document.getElementById('drawerSuccessPanel').style.display = 'block';
            } else {
                alert('Something went wrong. Please check your details and try again.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            console.error(err);
            alert('An error occurred during booking. Please try again.');
        });
    }
</script>
@endsection
