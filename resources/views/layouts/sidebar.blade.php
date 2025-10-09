<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>DarkPan</h3>
        </a>

        <div class="navbar-nav w-100">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
            </a>

            <!-- MAIN DATA DROPDOWN -->
            @php
                $isMainActive = request()->is('users*') || request()->is('handphone*') || request()->is('service-item*');
            @endphp
            <div class="nav-item dropdown {{ $isMainActive ? 'show' : '' }}">
                <a href="#" class="nav-link dropdown-toggle {{ $isMainActive ? 'active show' : '' }}" data-bs-toggle="dropdown">
                    <i class="fa fa-database me-2"></i>Main Data
                </a>
                <div class="dropdown-menu bg-transparent border-0 {{ $isMainActive ? 'show' : '' }}">
                    <a href="{{ route('users.index') }}" class="dropdown-item text-light {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i>User
                    </a>
                    <a href="{{ route('handphone.index') }}" class="dropdown-item text-light {{ request()->is('handphone*') ? 'active' : '' }}">
                        <i class="fa fa-mobile-alt me-2"></i>Handphone
                    </a>
                    <a href="" class="dropdown-item text-light {{ request()->is('service-item*') ? 'active' : '' }}">
                        <i class="fa fa-tools me-2"></i>Service Item
                    </a>
                </div>
            </div>

            <!-- SERVICE DROPDOWN -->
            @php
                $isServiceActive = request()->is('service-masuk*') || request()->is('service-proses*') || request()->is('service-selesai*');
            @endphp
            <div class="nav-item dropdown {{ $isServiceActive ? 'show' : '' }}">
                <a href="#" class="nav-link dropdown-toggle {{ $isServiceActive ? 'active show' : '' }}" data-bs-toggle="dropdown">
                    <i class="fa fa-cogs me-2"></i>Service
                </a>
                <div class="dropdown-menu bg-transparent border-0 {{ $isServiceActive ? 'show' : '' }}">
                    <a href="" class="dropdown-item text-light {{ request()->is('service-masuk*') ? 'active' : '' }}">
                        <i class="fa fa-inbox me-2"></i>Service Masuk
                    </a>
                    <a href="" class="dropdown-item text-light {{ request()->is('service-proses*') ? 'active' : '' }}">
                        <i class="fa fa-spinner me-2"></i>Service Proses
                    </a>
                    <a href="" class="dropdown-item text-light {{ request()->is('service-selesai*') ? 'active' : '' }}">
                        <i class="fa fa-check-circle me-2"></i>Service Selesai
                    </a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->
