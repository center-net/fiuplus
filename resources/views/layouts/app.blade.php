<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-lg-none" href="#">
                <i class="fas fa-graduation-cap"></i>
                {{ config('app.name', 'فيوبلس') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="topNavbar">
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                                <span class="visually-hidden">إشعارات غير مقروءة</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
                            aria-labelledby="notificationsDropdown" style="width: 300px;">
                            <li>
                                <h6 class="dropdown-header">الإشعارات</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-plus text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">تم تسجيل مستخدم جديد</p>
                                            <small class="text-muted">منذ 5 دقائق</small>
                                        </div>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="#">عرض كل الإشعارات</a></li>
                        </ul>
                    </li>

                    <!-- Messages Dropdown -->
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-envelope"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                2
                                <span class="visually-hidden">رسائل غير مقروءة</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
                            aria-labelledby="messagesDropdown" style="width: 300px;">
                            <li>
                                <h6 class="dropdown-header">الرسائل</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="https://github.com/mdo.png" width="32" height="32"
                                                class="rounded-circle" alt="صورة المستخدم">
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">أحمد محمد</p>
                                            <small class="text-muted">مرحباً، كيف حالك؟</small>
                                        </div>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="#">عرض كل الرسائل</a></li>
                        </ul>
                    </li>
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->getAvatarUrl() }}" alt="" width="32" height="32"
                                class="rounded-circle me-2">
                            <strong>{{ Auth::user()->name }}</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>الملف
                                    الشخصي</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>الإعدادات</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggle" class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop"></div>

    <div class="wrapper">
        <!-- Sidebar -->
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
                        <a href="#" class="nav-link active" aria-current="page">
                            <i class="fas fa-home me-2"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" class="nav-link text-white">
                            <i class="fas fa-users me-2"></i>
                            المستخدمين
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">
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

        <!-- Main Content -->
        <div class="main-wrapper">
            <div class="main-content">
                <!-- Loading Indicator for Livewire -->
                <div class="loading-indicator" wire:loading>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>

                <!-- Toast Container for Notifications -->
                <div class="toast-container">
                    @if (session()->has('message'))
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong class="me-auto">تنبيه</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{ session('message') }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <div class="container-fluid" style="margin-top: 40px;">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @livewireScripts
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-hide toasts after 5 seconds
                let toasts = document.querySelectorAll('.toast');
                toasts.forEach(function(toast) {
                    setTimeout(function() {
                        toast.classList.remove('show');
                    }, 5000);
                });

                // Sidebar Toggle Functionality
                const sidebar = document.querySelector('.sidebar');
                const sidebarBackdrop = document.querySelector('.sidebar-backdrop');
                const sidebarToggle = document.getElementById('sidebarToggle');

                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    sidebarBackdrop.classList.toggle('show');
                }

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                }

                if (sidebarBackdrop) {
                    sidebarBackdrop.addEventListener('click', toggleSidebar);
                }

                // Close sidebar when clicking on a link (mobile)
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 992) {
                            toggleSidebar();
                        }
                    });
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 992) {
                        sidebar.classList.remove('show');
                        sidebarBackdrop.classList.remove('show');
                    }
                });

                // Livewire event to close Bootstrap modal
                Livewire.on('closeModalEvent', () => {
                    const modalElement = document.getElementById('userFormModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(
                            modalElement);
                        modal.hide();
                    }
                });
            });
        </script>
</body>

</html>
