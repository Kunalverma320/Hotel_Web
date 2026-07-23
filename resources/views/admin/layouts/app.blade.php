<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'Hotel Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #ffffff;
            --sidebar-text: #475467;
            --sidebar-hover-bg: #f9fafb;
            --sidebar-hover-text: #1d2939;
            --sidebar-active-bg: #0d6efd;
            --sidebar-active-text: #ffffff;
            --sidebar-border: #f2f4f7;
            --body-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-color: #1d2939;
            --border-color: #f2f4f7;
            --navbar-bg: #ffffff;
        }
        [data-theme="dark"] {
            --sidebar-bg: #0f131a;
            --sidebar-text: #98a2b3;
            --sidebar-hover-bg: #1d2433;
            --sidebar-hover-text: #f9fafb;
            --sidebar-active-bg: #0d6efd;
            --sidebar-active-text: #ffffff;
            --sidebar-border: #1e2530;
            --body-bg: #090c10;
            --card-bg: #0f131a;
            --text-color: #f9fafb;
            --border-color: #1e2530;
            --navbar-bg: #0f131a;
        }
        body {
            background: var(--body-bg);
            color: var(--text-color);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 0.9rem;
            overflow-x: hidden;
        }
        #sidebar-wrapper {
            background: var(--sidebar-bg) !important;
            border-right: 1px solid var(--sidebar-border);
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar-wrapper.collapsed {
            left: -260px;
        }
        .sidebar-link {
            transition: all 0.25s ease;
            padding: 0.6rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--sidebar-text) !important;
            background: transparent !important;
            border-radius: 8px;
            margin: 0.15rem 0.75rem;
            border: none !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            opacity: 0.8;
            transition: transform 0.2s ease;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover-bg) !important;
            color: var(--sidebar-hover-text) !important;
            padding-left: 1.1rem;
        }
        .sidebar-link:hover i {
            transform: scale(1.15);
            opacity: 1;
        }
        .sidebar-link.active {
            background: var(--sidebar-active-bg) !important;
            color: var(--sidebar-active-text) !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
            font-weight: 600;
        }
        .sidebar-heading {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 1.25rem 1rem 0.5rem 1.25rem;
            color: #6c757d;
            text-transform: uppercase;
        }
        #page-content-wrapper {
            margin-left: 260px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }
        #page-content-wrapper.sidebar-collapsed {
            margin-left: 0;
        }
        #main-navbar {
            background: var(--navbar-bg) !important;
            border-color: var(--sidebar-border) !important;
        }
        .card {
            background: var(--card-bg);
            border-color: var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border-radius: 10px;
        }
        .table {
            color: var(--text-color);
        }
        .stat-card {
            border: 1px solid var(--border-color);
            border-left: 4px solid !important;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .badge-status-confirmed { background: #198754; }
        .badge-status-pending { background: #ffc107; color: #000; }
        .badge-status-cancelled { background: #dc3545; }
        .badge-status-checked-in { background: #0d6efd; }
        
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        @media (max-width: 768px) {
            #sidebar-wrapper {
                left: -260px;
            }
            #sidebar-wrapper.show {
                left: 0;
            }
            #page-content-wrapper {
                margin-left: 0 !important;
            }
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: skeleton-loading 1.5s infinite; border-radius: 4px; }
        @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        @include('admin.layouts.sidebar')
        <div id="page-content-wrapper" class="w-100">
            @include('admin.layouts.navbar')
            <div class="container-fluid px-4 py-3 fade-in">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li><i class="bi bi-x-circle me-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
            <footer class="px-4 py-3 border-top text-muted small">
                &copy; {{ date('Y') }} {{ config('app.name', 'Hotel Management System') }}. All rights reserved.
            </footer>
        </div>
    </div>

    {{-- Toast Container --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const toggleBtn = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar-wrapper');
            const wrapper = document.getElementById('page-content-wrapper');
            
            if (toggleBtn && sidebar && wrapper) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth > 768) {
                        sidebar.classList.toggle('collapsed');
                        wrapper.classList.toggle('sidebar-collapsed');
                    } else {
                        sidebar.classList.toggle('show');
                    }
                });
            }

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(event) {
                if (sidebar && toggleBtn && window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Dark Mode Toggle
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcon(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'light' ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', next);
                    document.documentElement.setAttribute('data-bs-theme', next);
                    localStorage.setItem('theme', next);
                    updateThemeIcon(next);
                });
            }

            function updateThemeIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
                }
            }

            // Initialize DataTables
            document.querySelectorAll('.data-table').forEach(function(table) {
                if (typeof $.fn.DataTable !== 'undefined') {
                    $(table).DataTable({
                        pageLength: 25,
                        order: [[0, 'desc']],
                        responsive: true,
                    });
                }
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                });
            }, 5000);

            // CSRF Token for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            var toastContainer = document.getElementById('toastContainer');
            var icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
            var html = '<div class="toast align-items-center text-white bg-' + type + ' border-0" role="alert"><div class="d-flex"><div class="toast-body"><i class="bi bi-' + icon + ' me-2"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
            toastContainer.insertAdjacentHTML('beforeend', html);
            var toastEl = toastContainer.lastElementChild;
            var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', function() { toastEl.remove(); });
        }

        // Confirm delete helper
        function confirmDelete(url, name) {
            if (confirm('Are you sure you want to delete "' + name + '"? This action cannot be undone.')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // AJAX form submit helper
        function ajaxSubmit(form, callback) {
            var formData = new FormData(form);
            fetch(form.action, {
                method: form.method || 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(data => {
                if (callback) callback(data);
            }).catch(err => {
                showToast('An error occurred', 'danger');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
