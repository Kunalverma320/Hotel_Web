<nav class="navbar navbar-expand-lg border-bottom px-4 py-2" id="main-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary" id="toggle-sidebar">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="d-none d-md-block">
                <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
            </div>
        </div>
        <div class="ms-auto d-flex align-items-center gap-3">
            {{-- Hotel Selector --}}
            @if(isset($allHotels) && $allHotels->count() > 0)
            <div class="dropdown d-none d-lg-block">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle fw-medium" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-building me-1"></i> {{ $currentHotel->name ?? 'All Hotels' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><h6 class="dropdown-header">Switch Active Hotel</h6></li>
                    <li>
                        <form method="POST" action="{{ route('admin.switch-hotel', 'all') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between {{ empty($currentHotel) ? 'active' : '' }}">
                                <span><i class="bi bi-buildings me-2"></i>All Hotels</span>
                                @if(empty($currentHotel))
                                    <i class="bi bi-check2 ms-2"></i>
                                @endif
                            </button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($allHotels as $h)
                        <li>
                            <form method="POST" action="{{ route('admin.switch-hotel', $h->id) }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between {{ (isset($currentHotel) && $currentHotel->id == $h->id) ? 'active' : '' }}">
                                    <span><i class="bi bi-building me-2"></i>{{ $h->name }}</span>
                                    @if(isset($currentHotel) && $currentHotel->id == $h->id)
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Dark Mode Toggle --}}
            <button class="btn btn-sm btn-outline-secondary" id="theme-toggle" title="Toggle Dark Mode">
                <i class="bi bi-moon" id="theme-icon"></i>
            </button>

            {{-- Notifications --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                        3
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width:320px;">
                    <h6 class="dropdown-header">Notifications</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item small" href="#">
                        <div class="fw-bold">New booking #BK-1234</div>
                        <div class="text-muted">2 minutes ago</div>
                    </a>
                    <a class="dropdown-item small" href="#">
                        <div class="fw-bold">Check-in completed</div>
                        <div class="text-muted">15 minutes ago</div>
                    </a>
                    <a class="dropdown-item small" href="#">
                        <div class="fw-bold">Low stock alert</div>
                        <div class="text-muted">1 hour ago</div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center small" href="{{ route('admin.notifications.index') }}">View all</a>
                </div>
            </div>

            {{-- User Menu --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.auth.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
