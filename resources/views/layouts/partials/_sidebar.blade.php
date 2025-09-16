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
            <span class="fs-4">{{ config('app.name', 'فيوبلس') }}</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('*/') ? 'active' : '' }}" aria-current="page">
                    <i class="fas fa-home me-2"></i>
                    الرئيسية
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="nav-link text-white {{ request()->is('*users') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    المستخدمين
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles') }}" class="nav-link text-white {{ request()->is('*roles') ? 'active' : '' }}">
                    <i class="fas fa-user-tag me-2"></i>
                    الأدوار
                </a>
            </li>
            <li>
                <a href="{{ route('admin.permissions') }}" class="nav-link text-white {{ request()->is('*permissions') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i>
                    الصلاحيات
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-globe me-2"></i>
                    الدول
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-city me-2"></i>
                    المدن
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    القرى
                </a>
            </li>
        </ul>
    </div>
</div>
