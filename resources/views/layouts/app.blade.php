<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    @include('layouts.partials._head')
</head>

<body>
    @include('layouts.partials._topbar')

    @auth
        @include('layouts.partials._sidebar')
    @endauth

    <div class="wrapper">
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
    </div>

    @include('layouts.partials._scripts')
</body>

</html>