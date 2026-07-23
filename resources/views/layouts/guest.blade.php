<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MakeMyTrip Hotels | Book Hotels, Resorts & Homestays')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #008cff;
            --primary-hover: #0076d6;
            --primary-light: rgba(0, 140, 255, 0.08);
            --accent: #ff4f4f;
            --bg-mmt: #051433;
            --bg-light: #f2f2f2;
            --card-bg: #ffffff;
            --text: #000000;
            --text-muted: #4a4a4a;
            --border: #dfdfdf;
            --header-gradient: linear-gradient(180deg, #152b53 0%, #0c1a30 100%);
            --search-btn-gradient: linear-gradient(93deg, #53b2fe 0%, #065af3 100%);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        [data-theme="dark"] {
            --bg-light: #0b0f19;
            --card-bg: #151f32;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: #2e3b4e;
            --primary-light: rgba(0, 140, 255, 0.15);
            --header-gradient: linear-gradient(180deg, #070e1b 0%, #02060f 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-light);
            color: var(--text);
            overflow-x: hidden;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header Navigation */
        .mmt-header {
            background: var(--header-gradient);
            padding: 0.8rem 0;
            position: relative;
            z-index: 100;
        }

        .navbar-brand img {
            height: 38px;
        }

        .login-btn-header {
            background: linear-gradient(90deg, #60b4ff 0%, #008cff 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(0, 140, 255, 0.25);
            transition: var(--transition);
            text-decoration: none;
        }

        .login-btn-header:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0, 140, 255, 0.4);
            color: #fff;
        }

        .theme-toggle-header {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            transition: var(--transition);
        }

        .theme-toggle-header:hover {
            background: var(--primary);
            color: #fff;
        }

        /* Footer styling */
        .mmt-footer {
            background: #0b1528;
            color: rgba(255,255,255,0.6);
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 4rem 0 2rem 0;
            margin-top: auto;
        }

        .mmt-footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mmt-footer-link {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: var(--transition);
            display: block;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .mmt-footer-link:hover {
            color: var(--primary);
            transform: translateX(3px);
        }

        .footer-social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            transition: var(--transition);
            text-decoration: none;
        }

        .footer-social-icon:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Common content cards */
        .info-page-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.04);
            padding: 3rem;
            margin: 3rem 0;
        }
        
        .info-page-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 1.5rem;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- MakeMyTrip Header Navigation -->
    <header class="mmt-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <svg viewBox="0 0 200 40" height="38" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="url(#mmtIconGrad)" />
                    <text x="20" y="25" font-family="'Outfit', sans-serif" font-weight="800" font-size="16" fill="#FFFFFF" text-anchor="middle">my</text>
                    <text x="48" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#FFFFFF">make</text>
                    <text x="100" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#ff5e36">my</text>
                    <text x="126" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#FFFFFF">trip</text>
                    <defs>
                        <linearGradient id="mmtIconGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#ff4f4f" />
                            <stop offset="100%" stop-color="#ff8f00" />
                        </linearGradient>
                    </defs>
                </svg>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <button class="theme-toggle-header" onclick="toggleTheme()" title="Toggle Dark Mode">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>
                @auth
                    @if(auth()->user()->roles()->exists())
                        <a href="{{ route('admin.dashboard') }}" class="login-btn-header" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-speedometer2"></i>
                            <span>Admin Panel</span>
                        </a>
                    @else
                        <span class="text-white fw-bold small"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
                        <a href="{{ route('my-bookings') }}" class="btn btn-sm btn-outline-light ms-2" style="border-radius: 8px; font-weight: 600; padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                            <i class="bi bi-journal-text me-1"></i>My Bookings
                        </a>
                    @endif
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" style="border-radius: 8px; padding: 0.5rem 1rem; font-weight: 600;">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                @else
                    <a href="{{ route('login') }}" class="login-btn-header">
                        <i class="bi bi-person-circle"></i>
                        <span>Login or Create Account</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @if(session('error'))
        <div class="container mt-4 mb-2">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center justify-content-between text-start" role="alert" style="border-radius: 12px; background: rgba(239, 68, 68, 0.15); color: #f87171; padding: 1rem 1.5rem;">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2" style="font-size:1.1rem;"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="position:static; padding:0; filter:invert(1);"></button>
            </div>
        </div>
        @endif
        @if(session('success'))
        <div class="container mt-4 mb-2">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center justify-content-between text-start" role="alert" style="border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: #34d399; padding: 1rem 1.5rem;">
                <div>
                    <i class="bi bi-check-circle-fill me-2" style="font-size:1.1rem;"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="position:static; padding:0; filter:invert(1);"></button>
            </div>
        </div>
        @endif
        @yield('content')
    </main>

    <!-- MakeMyTrip Styled Footer -->
    <footer class="mmt-footer">
        <div class="container">
            <div class="row g-4 mb-5 text-start">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <svg viewBox="0 0 200 40" height="32" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="18" fill="url(#mmtIconGrad)" />
                            <text x="20" y="25" font-family="'Outfit', sans-serif" font-weight="800" font-size="16" fill="#FFFFFF" text-anchor="middle">my</text>
                            <text x="48" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#FFFFFF">make</text>
                            <text x="100" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#ff5e36">my</text>
                            <text x="126" y="27" font-family="'Outfit', sans-serif" font-weight="800" font-size="20" fill="#FFFFFF">trip</text>
                        </svg>
                    </div>
                    <p class="small text-white-50 pe-lg-4">
                        Book flights, hotels, holiday packages, trains, buses, and cabs in just a few clicks. Your premium, unified travel partner.
                    </p>
                    <div class="mt-4">
                        <a href="#" class="footer-social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="footer-social-icon"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="footer-social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="footer-social-icon"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6">
                    <h5>Company</h5>
                    <a href="{{ url('/about') }}" class="mmt-footer-link">About Us</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Careers</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Corporate Travel</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">MMT Blog</a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <h5>Services</h5>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Book Hotels</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Book Flights</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Villas & Homestays</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Holiday Packages</a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <h5>Legal</h5>
                    <a href="{{ url('/privacy') }}" class="mmt-footer-link">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="mmt-footer-link">Terms & Conditions</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">User Agreement</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Cookie Settings</a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <h5>Support</h5>
                    <a href="{{ url('/contact') }}" class="mmt-footer-link">Contact Us</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Customer Support</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">FAQs</a>
                    <a href="{{ url('/') }}" class="mmt-footer-link">Cancel Booking</a>
                </div>
            </div>
            
            <div class="border-top border-secondary border-opacity-25 pt-4 text-center">
                <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} MakeMyTrip Mock. Powered by Laravel & Unified Hotel ERP.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcon(next);
        }
        
        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            }
        }

        // Initialize theme
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            updateThemeIcon(saved);
        })();
    </script>
    @yield('scripts')
</body>
</html>
