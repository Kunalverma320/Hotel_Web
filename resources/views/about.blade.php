@extends('layouts.guest')

@section('title', 'About Us | MakeMyTrip Hotels')

@section('content')
<div class="container my-5">
    <div class="info-page-card">
        <h1 class="info-page-title text-primary"><i class="bi bi-info-circle-fill me-2"></i>About MakeMyTrip</h1>
        <hr class="mb-4">
        
        <div class="row g-4">
            <div class="col-md-7">
                <p class="lead">
                    Nurtured from the seed of a single great idea, MakeMyTrip is India's leading online travel company. Founded by Deep Kalra in the year 2000, MakeMyTrip was created to empower travelers with instant bookings and comprehensive choices.
                </p>
                <p>
                    We began our journey in the US-India travel market, catering to the booking needs of the NRI community. Over the years, we have evolved into a one-stop-shop for all travel services including flight bookings, hotel reservations, homestays, holiday packages, and ground transportation.
                </p>
                <h5 class="fw-bold mt-4 text-dark">Our Mission & Values</h5>
                <p>
                    We aim to provide premium-grade technology solutions for booking systems, delivering seamless reservation workflows and robust properties support to corporate clients and individual customers alike.
                </p>
            </div>
            <div class="col-md-5">
                <div class="p-4 rounded-4" style="background: var(--bg-light); border: 1px solid var(--border);">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-award-fill text-warning me-2"></i>MMT Highlights</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div><strong>15M+</strong> Active Customers Globally</div>
                        </li>
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div><strong>50,000+</strong> Registered Hotel Partners</div>
                        </li>
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div><strong>24/7</strong> Direct Customer Assistance</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <div><strong>Secured</strong> API booking gateways</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
