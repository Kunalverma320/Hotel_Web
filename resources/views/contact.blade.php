@extends('layouts.guest')

@section('title', 'Contact Us | MakeMyTrip Hotels')

@section('content')
<div class="container my-5">
    <div class="info-page-card">
        <h1 class="info-page-title text-primary"><i class="bi bi-envelope-at-fill me-2"></i>Contact Us</h1>
        <hr class="mb-4">
        
        <div class="row g-4">
            <div class="col-md-6">
                <p class="lead">
                    Have questions about a booking, hotel partner integration, or special requests? Fill in the form and our support desk will contact you within 24 hours.
                </p>
                
                <form onsubmit="alert('Thank you for contacting us! We will get back to you shortly.'); event.preventDefault();" class="mt-4">
                    <div class="mb-3">
                        <label for="contactName" class="form-label fw-semibold text-muted small text-uppercase">Full Name</label>
                        <input type="text" class="form-control form-control-mmt" id="contactName" required placeholder="e.g. John Doe">
                    </div>
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label fw-semibold text-muted small text-uppercase">Email Address</label>
                        <input type="email" class="form-control form-control-mmt" id="contactEmail" required placeholder="e.g. john@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="contactSubject" class="form-label fw-semibold text-muted small text-uppercase">Subject</label>
                        <input type="text" class="form-control form-control-mmt" id="contactSubject" required placeholder="e.g. Booking Cancellation Support">
                    </div>
                    <div class="mb-4">
                        <label for="contactMessage" class="form-label fw-semibold text-muted small text-uppercase">Your Message</label>
                        <textarea class="form-control form-control-mmt" id="contactMessage" rows="4" required placeholder="Write details here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold" style="border-radius:12px;">Submit Message</button>
                </form>
            </div>
            
            <div class="col-md-6 ps-lg-5">
                <div class="p-4 rounded-4 mb-4" style="background: var(--bg-light); border: 1px solid var(--border);">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Global Headquarters</h5>
                    <p class="mb-1 text-dark fw-medium">MakeMyTrip Limited</p>
                    <p class="small text-muted mb-0">
                        DLF Cyber City, Phase III, Sector 25,<br>
                        Gurugram, Haryana 122002, India
                    </p>
                </div>

                <div class="p-4 rounded-4 mb-4" style="background: var(--bg-light); border: 1px solid var(--border);">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-telephone-fill text-primary me-2"></i>Call Center Support</h5>
                    <p class="mb-1 text-dark fw-medium">Hotels & Packages Support</p>
                    <p class="small text-muted mb-0">Toll Free: <strong>1-800-MMT-HELP (1-800-668-4357)</strong></p>
                    <p class="small text-muted mb-0">International: <strong>+91-124-4628747</strong></p>
                </div>

                <div class="p-4 rounded-4" style="background: var(--bg-light); border: 1px solid var(--border);">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-send-fill text-primary me-2"></i>Email Relations</h5>
                    <p class="small text-muted mb-1">Hotel Bookings: <a href="mailto:hotel.support@makemytrip.com" class="text-decoration-none">hotel.support@makemytrip.com</a></p>
                    <p class="small text-muted mb-0">Corporate Inquiries: <a href="mailto:corporate@makemytrip.com" class="text-decoration-none">corporate@makemytrip.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
