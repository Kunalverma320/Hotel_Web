<div class="sidebar bg-dark text-white" id="sidebar-wrapper" style="width:260px;min-height:100vh;position:fixed;top:0;left:0;z-index:1040;overflow-y:auto;transition:all .3s;">
    <div class="d-flex align-items-center justify-content-center py-3 border-bottom border-secondary">
        <i class="bi bi-building fs-4 me-2"></i>
        <span class="fs-5 fw-bold">Hotel ERP</span>
    </div>
    @if(isset($currentHotel) && $currentHotel)
    <div class="px-3 py-2 border-bottom border-secondary small">
        <i class="bi bi-geo-alt text-warning"></i> {{ $currentHotel->name }}
    </div>
    @endif
    <div class="list-group list-group-flush" id="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        {{-- Company & Hotel --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Company & Hotel</div>
        <a href="{{ route('admin.companies.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Companies
        </a>
        <a href="{{ route('admin.branches.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3"></i> Branches
        </a>
        <a href="{{ route('admin.hotels.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
            <i class="bi bi-hotel"></i> Hotels
        </a>

        {{-- Rooms --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Rooms</div>
        <a href="{{ route('admin.room-categories.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.room-categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Categories
        </a>
        <a href="{{ route('admin.room-types.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
            <i class="bi bi-layers"></i> Room Types
        </a>
        <a href="{{ route('admin.rooms.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Rooms
        </a>
        <a href="{{ route('admin.rooms.availability') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.rooms.availability') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Availability
        </a>

        {{-- Bookings --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Bookings</div>
        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i> All Bookings
        </a>
        <a href="{{ route('admin.bookings.create') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.bookings.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> New Booking
        </a>
        <a href="{{ route('admin.check-ins.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.check-ins.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i> Check-in
        </a>
        <a href="{{ route('admin.check-outs.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.check-outs.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-right"></i> Check-out
        </a>

        {{-- Guests & CRM --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Guests & CRM</div>
        <a href="{{ route('admin.guests.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.guests.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Guests
        </a>
        <a href="{{ route('admin.crm.leads') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.crm.*') ? 'active' : '' }}">
            <i class="bi bi-funnel"></i> CRM
        </a>

        {{-- Operations --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Operations</div>
        <a href="{{ route('admin.housekeeping.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.housekeeping.*') ? 'active' : '' }}">
            <i class="bi bi-brush"></i> Housekeeping
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Maintenance
        </a>

        {{-- Food & Beverage --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Food & Beverage</div>
        <a href="{{ route('admin.restaurant.dashboard') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.restaurant.*') ? 'active' : '' }}">
            <i class="bi bi-cup-hot"></i> Restaurant
        </a>
        <a href="{{ route('admin.restaurant.pos') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.restaurant.pos') ? 'active' : '' }}">
            <i class="bi bi-terminal"></i> POS
        </a>
        <a href="{{ route('admin.room-service.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.room-service.*') ? 'active' : '' }}">
            <i class="bi bi-cone-striped"></i> Room Service
        </a>

        {{-- Services --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Services</div>
        <a href="{{ route('admin.laundry.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.laundry.*') ? 'active' : '' }}">
            <i class="bi bi-tshirt"></i> Laundry
        </a>
        <a href="{{ route('admin.spa.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.spa.*') ? 'active' : '' }}">
            <i class="bi bi-droplet"></i> Spa
        </a>
        <a href="{{ route('admin.gym.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.gym.*') ? 'active' : '' }}">
            <i class="bi bi-bicycle"></i> Gym
        </a>
        <a href="{{ route('admin.pool.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.pool.*') ? 'active' : '' }}">
            <i class="bi bi-water"></i> Pool
        </a>
        <a href="{{ route('admin.events.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Events
        </a>

        {{-- Inventory & Purchases --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Inventory</div>
        <a href="{{ route('admin.inventory.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Inventory
        </a>
        <a href="{{ route('admin.purchases.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> Purchases
        </a>
        <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Suppliers
        </a>

        {{-- Finance --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Finance</div>
        <a href="{{ route('admin.finance.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Finance
        </a>
        <a href="{{ route('admin.finance.income') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.finance.income') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-circle"></i> Income
        </a>
        <a href="{{ route('admin.finance.expense') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.finance.expense') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-circle"></i> Expenses
        </a>
        <a href="{{ route('admin.finance.journal') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.finance.journal') ? 'active' : '' }}">
            <i class="bi bi-journal"></i> Journal
        </a>
        <a href="{{ route('admin.finance.coa') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.finance.coa*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2"></i> Chart of Accounts
        </a>
        <a href="{{ route('admin.reports.finance.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.reports.finance.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Finance Reports
        </a>

        {{-- HRMS --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">HRMS</div>
        <a href="{{ route('admin.employees.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Employees
        </a>
        <a href="{{ route('admin.departments.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Departments
        </a>
        <a href="{{ route('admin.attendance.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Attendance
        </a>
        <a href="{{ route('admin.leaves.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-x"></i> Leaves
        </a>
        <a href="{{ route('admin.payroll.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Payroll
        </a>
        <a href="{{ route('admin.shifts.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat"></i> Shifts
        </a>

        {{-- Tasks & Documents --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Tasks & Documents</div>
        <a href="{{ route('admin.tasks.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Tasks
        </a>
        <a href="{{ route('admin.documents.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark"></i> Documents
        </a>

        {{-- CMS & Marketing --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">CMS & Marketing</div>
        <a href="{{ route('admin.marketing.coupons.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.marketing.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> Coupons
        </a>
        <a href="{{ route('admin.marketing.gift-cards.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.marketing.gift-cards.*') ? 'active' : '' }}">
            <i class="bi bi-gift"></i> Gift Cards
        </a>
        <a href="{{ route('admin.marketing.campaigns.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.marketing.campaigns.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i> Campaigns
        </a>

        {{-- Communications --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Communications</div>
        <a href="{{ route('admin.communications.email.templates') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.communications.email.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Email
        </a>
        <a href="{{ route('admin.communications.sms.templates') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.communications.sms.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i> SMS
        </a>
        <a href="{{ route('admin.communications.whatsapp.templates') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.communications.whatsapp.*') ? 'active' : '' }}">
            <i class="bi bi-whatsapp"></i> WhatsApp
        </a>

        {{-- Reports & Analytics --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Reports & Analytics</div>
        <a href="{{ route('admin.reports.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Reports
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Analytics
        </a>

        {{-- Administration --}}
        <div class="sidebar-heading small text-muted text-uppercase px-3 mt-3 mb-1">Administration</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="{{ route('admin.security.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.security.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Security
        </a>
        <a href="{{ route('admin.audit.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Audit Logs
        </a>
        <a href="{{ route('admin.backup.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
            <i class="bi bi-cloud-arrow-up"></i> Backups
        </a>
        <a href="{{ route('admin.export.bookings') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
            <i class="bi bi-download"></i> Export
        </a>
        <a href="{{ route('admin.import.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
            <i class="bi bi-upload"></i> Import
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="sidebar-link list-group-item list-group-item-action bg-dark text-white border-0 {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
        </a>
    </div>
    <div class="mt-auto p-3 border-top border-secondary small text-muted">
        &copy; {{ date('Y') }} Hotel ERP
    </div>
</div>
