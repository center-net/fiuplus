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

    <!-- Sidebar Toggle Button -->
    <button id="sidebarToggle" class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;">
        <i class="fas fa-bars"></i>
    </button>
    <div class="wrapper">

        <!-- Main Content -->
            <!-- Toast Container for Notifications -->
            <div class="toast-container">
                @if (session()->has('message'))
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <strong class="me-auto">{{ __('app.alert') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ session('message') }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <div class="container-fluid" style="margin-top: 60px;">
                {{ $slot }}
            </div>
    </div>

    @livewireScripts
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
