<!-- Sidebar Toggle Button -->
<button id="sidebarToggle" class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar Backdrop -->
<div class="sidebar-backdrop"></div>

<div class="sidebar">
    <div class="d-flex flex-column p-3 text-white" style="width: 280px;">
        <a href="/"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="fas fa-graduation-cap fs-4 me-2"></i>
            <span class="fs-4">{{ config('app.name', 'FiuPlus') }}</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('*/') ? 'active' : '' }}" aria-current="page">
                    <i class="fas fa-home me-2"></i>
                    {{ __('app.home') }}
                </a>
            </li>
            @can('viewAny', \App\Models\User::class)
            <li>
                <a href="{{ route('admin.users') }}" class="nav-link text-white {{ request()->is('*users') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    {{ __('app.users') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Role::class)
            <li>
                <a href="{{ route('admin.roles') }}" class="nav-link text-white {{ request()->is('*roles') ? 'active' : '' }}">
                    <i class="fas fa-user-tag me-2"></i>
                    {{ __('app.roles') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Permission::class)
            <li>
                <a href="{{ route('admin.permissions') }}" class="nav-link text-white {{ request()->is('*permissions') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i>
                    {{ __('app.permissions') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Country::class)
            <li>
                <a href="{{ route('admin.countries') }}" class="nav-link text-white {{ request()->is('*countries') ? 'active' : ''}}">
                    <i class="fas fa-globe me-2"></i>
                    {{ __('app.countries') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\City::class)
            <li>
                <a href="{{ route('admin.cities') }}" class="nav-link text-white {{ request()->is('*cities') ? 'active' : ''}}">
                    <i class="fas fa-city me-2"></i>
                    {{ __('app.cities') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Village::class)
            <li>
                <a href="{{ route('admin.villages') }}" class="nav-link text-white {{ request()->is('*villages') ? 'active' : ''}}">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ __('app.villages') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\Store::class)
            <li>
                <a href="{{ route('admin.stores') }}" class="nav-link text-white {{ request()->is('*stores') ? 'active' : ''}}">
                    <i class="fas fa-store me-2"></i>
                    {{ __('app.stores') }}
                </a>
            </li>
            @endcan
            @can('viewAny', \App\Models\StoreCategory::class)
            <li>
                <a href="{{ route('admin.store-categories') }}" class="nav-link text-white {{ request()->is('*store-categories') ? 'active' : ''}}">
                    <i class="fas fa-tags me-2"></i>
                    {{ __('app.store_categories') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>
