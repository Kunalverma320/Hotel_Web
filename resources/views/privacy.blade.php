@extends('layouts.guest')

@section('title', 'Privacy Policy | MakeMyTrip Hotels')

@section('content')
<div class="container my-5">
    <div class="info-page-card text-start">
        <h1 class="info-page-title text-primary"><i class="bi bi-shield-lock-fill me-2"></i>Privacy Policy</h1>
        <p class="text-muted">Last Updated: July 2026</p>
        <hr class="mb-4">
        
        <div class="lh-lg">
            <p>
                At MakeMyTrip, we respect your privacy and value the trust you place in us. This Privacy Policy describes how we collect, use, process, and disclose your personal information in connection with your access to and use of the MakeMyTrip platform.
            </p>

            <h5 class="fw-bold mt-4 text-dark">1. Information We Collect</h5>
            <p>
                We collect personal information that you provide directly to us when setting up an account, booking a hotel, or sending support inquiries:
            </p>
            <ul>
                <li>Contact Details: Full name, phone number, physical address, and email address.</li>
                <li>Booking Data: Check-in/check-out dates, selected hotel, room details, and guest configurations.</li>
                <li>Billing Details: Payment details, credit card indices, and invoicing receipts processed through secured third-party providers.</li>
            </ul>

            <h5 class="fw-bold mt-4 text-dark">2. How We Use Your Information</h5>
            <p>
                We use the information we collect to fulfill your booking inquiries, coordinate with hotel property partners, process check-ins, send transaction notifications, and optimize search algorithms.
            </p>

            <h5 class="fw-bold mt-4 text-dark">3. Data Sharing and Transfer</h5>
            <p>
                We share relevant booking details with the respective hotel management partners (e.g. Grand Luxury Hotel NYC, Seaside Resort Miami) to complete check-in procedures. We do not sell or lease your contact information to third-party marketing companies.
            </p>

            <h5 class="fw-bold mt-4 text-dark">4. Safety Controls & Security</h5>
            <p>
                All data transfers are protected under secure HTTPS/TLS protocols. Client database entries are securely archived inside protected database infrastructures.
            </p>

            <h5 class="fw-bold mt-4 text-dark">5. Contact Information</h5>
            <p>
                If you have questions or complaints about this Privacy Policy, you may contact our privacy officer at <a href="mailto:privacy@makemytrip.com" class="text-decoration-none">privacy@makemytrip.com</a>.
            </p>
        </div>
    </div>
</div>
@endsection
