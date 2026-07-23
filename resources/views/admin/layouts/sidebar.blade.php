<div class="sidebar" id="sidebar-wrapper">
    <div class="d-flex align-items-center justify-content-center py-3 border-bottom" style="border-color: var(--sidebar-border) !important;">
        <i class="bi bi-building fs-4 me-2"></i>
        <span class="fs-5 fw-bold">Hotel ERP</span>
    </div>
    @if(isset($currentHotel) && $currentHotel)
    <div class="px-3 py-2 border-bottom small" style="border-color: var(--sidebar-border) !important;">
        <i class="bi bi-geo-alt text-warning"></i> {{ $currentHotel->name }}
    </div>
    @endif
    <div class="list-group list-group-flush" id="sidebar-nav">
        @can('view dashboard')
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        @endcan

        {{-- Company & Hotel --}}
        @if(auth()->user()->can('view companies') || auth()->user()->can('view branches') || auth()->user()->can('view hotels'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Company & Hotel</div>
        @can('view companies')
        <a href="{{ route('admin.companies.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Companies
        </a>
        @endcan
        @can('view branches')
        <a href="{{ route('admin.branches.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i> Branches
        </a>
        @endcan
        @can('view hotels')
        <a href="{{ route('admin.hotels.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
            <i class="bi bi-hotel"></i> Hotels
        </a>
        @endcan
        @endif

        {{-- Rooms --}}
        @if(auth()->user()->can('view rooms') || auth()->user()->can('view room-types'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Rooms</div>
        @can('view rooms')
        <a href="{{ route('admin.room-categories.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.room-categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Categories
        </a>
        @endcan
        @can('view room-types')
        <a href="{{ route('admin.room-types.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
            <i class="bi bi-layers"></i> Room Types
        </a>
        @endcan
        @can('view rooms')
        <a href="{{ route('admin.rooms.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Rooms
        </a>
        <a href="{{ route('admin.rooms.availability') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.rooms.availability') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Availability
        </a>
        @endcan
        @endif

        {{-- Bookings --}}
        @if(auth()->user()->can('view bookings') || auth()->user()->can('view check-ins') || auth()->user()->can('view check-outs'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Bookings</div>
        @can('view bookings')
        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i> All Bookings
        </a>
        @can('create bookings')
        <a href="{{ route('admin.bookings.create') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.bookings.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> New Booking
        </a>
        @endcan
        @endcan
        @can('view check-ins')
        <a href="{{ route('admin.check-ins.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.check-ins.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i> Check-in
        </a>
        @endcan
        @can('view check-outs')
        <a href="{{ route('admin.check-outs.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.check-outs.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-right"></i> Check-out
        </a>
        @endcan
        @endif

        {{-- Guests & CRM --}}
        @if(auth()->user()->can('view guests') || auth()->user()->can('view crm'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Guests & CRM</div>
        @can('view guests')
        <a href="{{ route('admin.guests.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.guests.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Guests
        </a>
        @endcan
        @can('view crm')
        <a href="{{ route('admin.crm.leads') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.crm.*') ? 'active' : '' }}">
            <i class="bi bi-funnel"></i> CRM
        </a>
        @endcan
        @endif

        {{-- Operations --}}
        @if(auth()->user()->can('view housekeeping') || auth()->user()->can('view maintenance'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Operations</div>
        @can('view housekeeping')
        <a href="{{ route('admin.housekeeping.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.housekeeping.*') ? 'active' : '' }}">
            <i class="bi bi-brush"></i> Housekeeping
        </a>
        @endcan
        @can('view maintenance')
        <a href="{{ route('admin.maintenance.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Maintenance
        </a>
        @endcan
        @endif

        {{-- Food & Beverage --}}
        @if(auth()->user()->can('view restaurant') || auth()->user()->can('view room-service'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Food & Beverage</div>
        @can('view restaurant')
        <a href="{{ route('admin.restaurant.dashboard') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.restaurant.*') ? 'active' : '' }}">
            <i class="bi bi-cup-hot"></i> Restaurant
        </a>
        <a href="{{ route('admin.restaurant.pos') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.restaurant.pos') ? 'active' : '' }}">
            <i class="bi bi-terminal"></i> POS
        </a>
        @endcan
        @can('view room-service')
        <a href="{{ route('admin.room-service.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.room-service.*') ? 'active' : '' }}">
            <i class="bi bi-cone-striped"></i> Room Service
        </a>
        @endcan
        @endif

        {{-- Services --}}
        @if(auth()->user()->can('view laundry') || auth()->user()->can('view spa') || auth()->user()->can('view gym') || auth()->user()->can('view pool') || auth()->user()->can('view events'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Services</div>
        @can('view laundry')
        <a href="{{ route('admin.laundry.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.laundry.*') ? 'active' : '' }}">
            <i class="bi bi-tshirt"></i> Laundry
        </a>
        @endcan
        @can('view spa')
        <a href="{{ route('admin.spa.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.spa.*') ? 'active' : '' }}">
            <i class="bi bi-droplet"></i> Spa
        </a>
        @endcan
        @can('view gym')
        <a href="{{ route('admin.gym.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.gym.*') ? 'active' : '' }}">
            <i class="bi bi-bicycle"></i> Gym
        </a>
        @endcan
        @can('view pool')
        <a href="{{ route('admin.pool.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.pool.*') ? 'active' : '' }}">
            <i class="bi bi-water"></i> Pool
        </a>
        @endcan
        @can('view events')
        <a href="{{ route('admin.events.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Events
        </a>
        @endcan
        @endif

        {{-- Inventory & Purchases --}}
        @if(auth()->user()->can('view inventory') || auth()->user()->can('view purchases') || auth()->user()->can('view suppliers'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Inventory</div>
        @can('view inventory')
        <a href="{{ route('admin.inventory.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Inventory
        </a>
        @endcan
        @can('view purchases')
        <a href="{{ route('admin.purchases.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> Purchases
        </a>
        @endcan
        @can('view suppliers')
        <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Suppliers
        </a>
        @endcan
        @endif

        {{-- Finance --}}
        @if(auth()->user()->can('view finance'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Finance</div>
        <a href="{{ route('admin.finance.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Finance
        </a>
        <a href="{{ route('admin.finance.income') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.finance.income') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-circle"></i> Income
        </a>
        <a href="{{ route('admin.finance.expense') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.finance.expense') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-circle"></i> Expenses
        </a>
        <a href="{{ route('admin.finance.journal') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.finance.journal') ? 'active' : '' }}">
            <i class="bi bi-journal"></i> Journal
        </a>
        <a href="{{ route('admin.finance.coa') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.finance.coa*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2"></i> Chart of Accounts
        </a>
        <a href="{{ route('admin.reports.finance.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.reports.finance.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Finance Reports
        </a>
        @endif

        {{-- HRMS --}}
        @if(auth()->user()->can('view employees') || auth()->user()->can('view departments') || auth()->user()->can('view attendance') || auth()->user()->can('view leaves') || auth()->user()->can('view payroll') || auth()->user()->can('view shifts'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">HRMS</div>
        @can('view employees')
        <a href="{{ route('admin.employees.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Employees
        </a>
        @endcan
        @can('view departments')
        <a href="{{ route('admin.departments.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Departments
        </a>
        @endcan
        @can('view attendance')
        <a href="{{ route('admin.attendance.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Attendance
        </a>
        @endcan
        @can('view leaves')
        <a href="{{ route('admin.leaves.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-x"></i> Leaves
        </a>
        @endcan
        @can('view payroll')
        <a href="{{ route('admin.payroll.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Payroll
        </a>
        @endcan
        @can('view shifts')
        <a href="{{ route('admin.shifts.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat"></i> Shifts
        </a>
        @endcan
        @endif

        {{-- Tasks & Documents --}}
        @if(auth()->user()->can('view tasks') || auth()->user()->can('view documents'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Tasks & Documents</div>
        @can('view tasks')
        <a href="{{ route('admin.tasks.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Tasks
        </a>
        @endcan
        @can('view documents')
        <a href="{{ route('admin.documents.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark"></i> Documents
        </a>
        @endcan
        @endif

        {{-- CMS & Marketing --}}
        @if(auth()->user()->can('view marketing'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">CMS & Marketing</div>
        <a href="{{ route('admin.marketing.coupons.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.marketing.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> Coupons
        </a>
        <a href="{{ route('admin.marketing.gift-cards.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.marketing.gift-cards.*') ? 'active' : '' }}">
            <i class="bi bi-gift"></i> Gift Cards
        </a>
        <a href="{{ route('admin.marketing.campaigns.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.marketing.campaigns.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i> Campaigns
        </a>
        @endif

        {{-- Communications --}}
        @if(auth()->user()->can('view communications'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Communications</div>
        <a href="{{ route('admin.communications.email.templates') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.communications.email.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Email
        </a>
        <a href="{{ route('admin.communications.sms.templates') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.communications.sms.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i> SMS
        </a>
        <a href="{{ route('admin.communications.whatsapp.templates') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.communications.whatsapp.*') ? 'active' : '' }}">
            <i class="bi bi-whatsapp"></i> WhatsApp
        </a>
        @endif

        {{-- Reports & Analytics --}}
        @if(auth()->user()->can('view reports') || auth()->user()->can('view analytics'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Reports & Analytics</div>
        @can('view reports')
        <a href="{{ route('admin.reports.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Reports
        </a>
        @endcan
        @can('view analytics')
        <a href="{{ route('admin.analytics.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Analytics
        </a>
        @endcan
        @endif

        {{-- Administration --}}
        @if(auth()->user()->can('view settings') || auth()->user()->can('view security') || auth()->user()->can('view audit') || auth()->user()->can('view backup') || auth()->user()->can('view export') || auth()->user()->can('view import') || auth()->user()->can('view notifications'))
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Administration</div>
        @can('view settings')
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        @endcan
        @can('view security')
        <a href="{{ route('admin.security.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.security.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Security
        </a>
        @endcan
        @can('view audit')
        <a href="{{ route('admin.audit.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Audit Logs
        </a>
        @endcan
        @can('view backup')
        <a href="{{ route('admin.backup.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
            <i class="bi bi-cloud-arrow-up"></i> Backups
        </a>
        @endcan
        @can('view export')
        <a href="{{ route('admin.export.bookings') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
            <i class="bi bi-download"></i> Export
        </a>
        @endcan
        @can('view import')
        <a href="{{ route('admin.import.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
            <i class="bi bi-upload"></i> Import
        </a>
        @endcan
        @can('view notifications')
        <a href="{{ route('admin.notifications.index') }}" class="sidebar-link list-group-item list-group-item-action {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
        </a>
        @endcan
        @endif
    </div>
    <div class="mt-auto p-3 border-top small text-muted" style="border-color: var(--sidebar-border) !important;">
        &copy; {{ date('Y') }} Hotel ERP
    </div>
</div>
