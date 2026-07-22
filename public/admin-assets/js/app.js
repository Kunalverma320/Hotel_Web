/* ==========================================================
   Hotel Management System - Admin JS
   ========================================================== */

(function () {
    'use strict';

    /* --------------------------------------------------
       CSRF Token Setup for AJAX
    -------------------------------------------------- */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    });

    /* --------------------------------------------------
       Sidebar Toggle
    -------------------------------------------------- */
    window.toggleSidebar = function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const wrapper = document.getElementById('adminWrapper');

        if (window.innerWidth <= 991) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        } else {
            wrapper.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('collapsed');
        }
    };

    /* --------------------------------------------------
       Sidebar Submenu Toggle
    -------------------------------------------------- */
    window.toggleSubmenu = function (e, link) {
        e.preventDefault();
        const menuItem = link.closest('.nav-item');
        const submenu = menuItem.querySelector('.submenu');

        // Close other open submenus
        document.querySelectorAll('.nav-item.has-submenu.open').forEach(function (item) {
            if (item !== menuItem) {
                item.classList.remove('open');
            }
        });

        menuItem.classList.toggle('open');
    };

    // Initialize open submenus on page load
    function initSubmenus() {
        document.querySelectorAll('.nav-item.has-submenu.active').forEach(function (item) {
            item.classList.add('open');
        });
    }

    /* --------------------------------------------------
       Dark / Light Mode Toggle
    -------------------------------------------------- */
    window.toggleTheme = function () {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('admin-theme', next);
        updateThemeIcon(next);
    };

    function updateThemeIcon(theme) {
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }
    }

    function initTheme() {
        const saved = localStorage.getItem('admin-theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
        updateThemeIcon(saved);
    }

    /* --------------------------------------------------
       Fullscreen Toggle
    -------------------------------------------------- */
    window.toggleFullscreen = function () {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(function () { });
            const icon = document.getElementById('fullscreenIcon');
            if (icon) { icon.className = 'bi bi-fullscreen-exit'; }
        } else {
            document.exitFullscreen();
            const icon = document.getElementById('fullscreenIcon');
            if (icon) { icon.className = 'bi bi-fullscreen'; }
        }
    };

    /* --------------------------------------------------
       Hotel Selector (AJAX Context Switch)
    -------------------------------------------------- */
    window.switchHotel = function (hotelId) {
        if (!hotelId) return;
        showLoading();
        $.ajax({
            url: '/admin/switch-hotel',
            method: 'POST',
            data: { hotel_id: hotelId },
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                }
            },
            error: function () {
                showToast('Failed to switch hotel', 'danger');
                hideLoading();
            }
        });
    };

    /* --------------------------------------------------
       DataTable Initialization
    -------------------------------------------------- */
    function initDataTable() {
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
                },
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
            });
        }
    }

    /* --------------------------------------------------
       Toast Notification
    -------------------------------------------------- */
    window.showToast = function (message, type, duration) {
        type = type || 'success';
        duration = duration || 5000;
        var container = document.getElementById('toastContainer');
        if (!container) return;

        var icons = {
            success: 'bi-check-circle-fill',
            danger: 'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-circle-fill',
            info: 'bi-info-circle-fill'
        };
        var colors = {
            success: '#22c55e',
            danger: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        var toastId = 'toast-' + Date.now();
        var html = '<div id="' + toastId + '" class="toast show align-items-center text-white border-0 mb-2" role="alert" style="background:' + colors[type] + ';border-radius:10px;min-width:280px;">'
            + '<div class="d-flex align-items-center px-3 py-2">'
            + '<i class="bi ' + (icons[type] || icons.info) + ' me-2" style="font-size:1.125rem;"></i>'
            + '<span class="flex-grow-1">' + message + '</span>'
            + '<button type="button" class="btn-close btn-close-white ms-2" onclick="this.closest(\'.toast\').remove()"></button>'
            + '</div></div>';

        container.insertAdjacentHTML('beforeend', html);

        setTimeout(function () {
            var el = document.getElementById(toastId);
            if (el) {
                el.style.transition = 'opacity 0.3s, transform 0.3s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(function () { el.remove(); }, 300);
            }
        }, duration);
    };

    /* --------------------------------------------------
       Confirm Delete Modal
    -------------------------------------------------- */
    window.confirmDelete = function (url, message) {
        var modal = document.getElementById('confirmDeleteModal');
        var form = document.getElementById('deleteForm');
        var text = document.getElementById('deleteModalText');

        if (form) form.setAttribute('action', url);
        if (text) text.textContent = message || 'This action cannot be undone.';

        var bsModal = bootstrap.Modal.getOrCreateInstance(modal);
        bsModal.show();
    };

    /* --------------------------------------------------
       AJAX Form Submission Helper
    -------------------------------------------------- */
    window.ajaxFormSubmit = function (formElement, options) {
        options = options || {};
        var form = typeof formElement === 'string' ? document.querySelector(formElement) : formElement;
        if (!form) return;

        var formData = new FormData(form);
        var btn = form.querySelector('[type="submit"]');
        var originalText = btn ? btn.innerHTML : '';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';
        }

        $.ajax({
            url: form.getAttribute('action') || window.location.href,
            method: form.getAttribute('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
                if (options.onSuccess) {
                    options.onSuccess(response);
                } else {
                    showToast(response.message || 'Operation completed successfully', 'success');
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else if (options.reload !== false) {
                        window.location.reload();
                    }
                }
            },
            error: function (xhr) {
                if (btn) { btn.disabled = false; btn.innerHTML = originalText; }
                var msg = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showToast(msg, 'danger');
                if (options.onError) options.onError(xhr);
            }
        });
    };

    /* --------------------------------------------------
       Loading Spinner Show / Hide
    -------------------------------------------------- */
    window.showLoading = function () {
        var existing = document.getElementById('globalLoadingOverlay');
        if (existing) return;
        var overlay = document.createElement('div');
        overlay.id = 'globalLoadingOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.3);z-index:9999;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(2px);';
        overlay.innerHTML = '<div class="loading-spinner" style="width:40px;height:40px;border-width:3px;"></div>';
        document.body.appendChild(overlay);
    };

    window.hideLoading = function () {
        var overlay = document.getElementById('globalLoadingOverlay');
        if (overlay) overlay.remove();
    };

    /* --------------------------------------------------
       Chart.js Default Settings
    -------------------------------------------------- */
    function initChartDefaults() {
        if (typeof Chart === 'undefined') return;
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 16;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15,23,42,0.9)';
        Chart.defaults.plugins.tooltip.titleFont = { weight: '600' };
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.displayColors = false;
        Chart.defaults.animation.duration = 800;
        Chart.defaults.animation.easing = 'easeOutQuart';
    }

    /* --------------------------------------------------
       Auto-dismiss Alerts
    -------------------------------------------------- */
    function initAlertDismiss() {
        document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
            setTimeout(function () {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 8000);
        });
    }

    /* --------------------------------------------------
       Sidebar Responsive Close
    -------------------------------------------------- */
    function handleResize() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth > 991) {
            if (sidebar) sidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
        }
    }

    /* --------------------------------------------------
       Close sidebar on link click (mobile)
    -------------------------------------------------- */
    function initSidebarCloseOnNav() {
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 991) {
                    var sidebar = document.getElementById('sidebar');
                    var overlay = document.getElementById('sidebarOverlay');
                    if (sidebar) sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('show');
                }
            });
        });
    }

    /* --------------------------------------------------
       Init Everything on DOM Ready
    -------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function () {
        initTheme();
        initSubmenus();
        initDataTable();
        initChartDefaults();
        initAlertDismiss();
        initSidebarCloseOnNav();
        window.addEventListener('resize', handleResize);
    });

})();
