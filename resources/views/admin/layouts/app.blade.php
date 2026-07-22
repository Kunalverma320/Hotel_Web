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
    <style>
        :root { --sidebar-bg: #212529; --sidebar-text: #fff; --body-bg: #f8f9fa; --card-bg: #fff; --text-color: #212529; --border-color: #dee2e6; }
        [data-theme="dark"] { --body-bg: #1a1d21; --card-bg: #2b2f33; --text-color: #e0e0e0; --border-color: #444; }
        body { background: var(--body-bg); color: var(--text-color); font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        #sidebar-wrapper { background: var(--sidebar-bg) !important; }
        .sidebar-link { transition: all .2s; padding: 0.5rem 1rem; font-size: 0.9rem; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1) !important; padding-left: 1.25rem; }
        .sidebar-link.active { background: rgba(255,255,255,0.15) !important; border-left: 3px solid #0d6efd !important; }
        .sidebar-link i { width: 20px; margin-right: 8px; text-align: center; }
        .sidebar-heading { font-size: 0.7rem; letter-spacing: 1px; padding: 0.5rem 1rem; }
        #page-content-wrapper { margin-left: 260px; transition: margin .3s; }
        #page-content-wrapper.sidebar-collapsed { margin-left: 0; }
        .card { background: var(--card-bg); border-color: var(--border-color); }
        .table { color: var(--text-color); }
        .stat-card { border-left: 4px solid; border-radius: 8px; transition: transform .2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        [data-theme="dark"] .navbar { background: #2b2f33 !important; border-color: #444 !important; color: #e0e0e0; }
        [data-theme="dark"] .dropdown-menu { background: #2b2f33; border-color: #444; }
        [data-theme="dark"] .dropdown-item { color: #e0e0e0; }
        [data-theme="dark"] .dropdown-item:hover { background: rgba(255,255,255,0.1); }
        .badge-status-confirmed { background: #198754; }
        .badge-status-pending { background: #ffc107; color: #000; }
        .badge-status-cancelled { background: #dc3545; }
        .badge-status-checked-in { background: #0d6efd; }
        @media (max-width: 768px) {
            #sidebar-wrapper { transform: translateX(-100%); }
            #sidebar-wrapper.show { transform: translateX(0); }
            #page-content-wrapper { margin-left: 0 !important; }
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
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    wrapper.classList.toggle('sidebar-collapsed');
                });
            }

            // Dark Mode Toggle
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'light' ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', next);
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
