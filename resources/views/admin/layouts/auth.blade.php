<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - {{ config('app.name', 'Hotel Management') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --auth-bg: #f0f2f5;
            --auth-card-bg: #ffffff;
            --auth-text: #1a1a2e;
            --auth-text-muted: #6c757d;
            --auth-border: #dee2e6;
            --auth-primary: #4f46e5;
            --auth-primary-hover: #4338ca;
            --auth-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        [data-theme="dark"] {
            --auth-bg: #0f172a;
            --auth-card-bg: #1e293b;
            --auth-text: #f1f5f9;
            --auth-text-muted: #94a3b8;
            --auth-border: #334155;
            --auth-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--auth-bg);
            color: var(--auth-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            transition: background 0.3s, color 0.3s;
        }
        .auth-wrapper {
            width: 100%;
            max-width: 440px;
        }
        .auth-card {
            background: var(--auth-card-bg);
            border-radius: 16px;
            box-shadow: var(--auth-shadow);
            padding: 2.5rem;
            border: 1px solid var(--auth-border);
            transition: background 0.3s, border-color 0.3s;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo img {
            max-height: 56px;
            margin-bottom: 0.75rem;
        }
        .auth-logo h4 {
            font-weight: 700;
            color: var(--auth-text);
            margin-bottom: 0.25rem;
        }
        .auth-logo p {
            color: var(--auth-text-muted);
            font-size: 0.875rem;
        }
        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--auth-text);
            margin-bottom: 0.375rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--auth-border);
            background: var(--auth-card-bg);
            color: var(--auth-text);
            font-size: 0.9375rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
        }
        .btn-auth {
            background: var(--auth-primary);
            border: none;
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            color: #fff;
            transition: background 0.2s;
        }
        .btn-auth:hover { background: var(--auth-primary-hover); color: #fff; }
        .auth-links { text-align: center; margin-top: 1.25rem; }
        .auth-links a {
            color: var(--auth-primary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .auth-links a:hover { text-decoration: underline; }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8125rem;
            color: var(--auth-text-muted);
        }
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: var(--auth-card-bg);
            border: 1px solid var(--auth-border);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.125rem;
            color: var(--auth-text);
            transition: all 0.3s;
            z-index: 100;
        }
        .theme-toggle:hover { background: var(--auth-primary); color: #fff; border-color: var(--auth-primary); }
    </style>
    @stack('styles')
</head>
<body>
    <button type="button" class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
    </button>

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                @else
                    <div style="width:56px;height:56px;background:var(--auth-primary);border-radius:14px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                        <i class="bi bi-building text-white" style="font-size:1.5rem;"></i>
                    </div>
                @endif
                <h4>{{ config('app.name', 'Hotel Management') }}</h4>
                <p>Admin Panel</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <div class="auth-footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'Hotel Management') }}. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
            icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            updateThemeIcon(saved);
        })();
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(el) {
                var alert = bootstrap.Alert.getOrCreateInstance(el);
                alert.close();
            });
        }, 8000);
    </script>
    @stack('scripts')
</body>
</html>
