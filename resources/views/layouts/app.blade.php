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
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
                    <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <strong class="me-auto">تنبيه</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <!-- Message will be inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="container-fluid" style="margin-top: 40px;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const toastEl = document.getElementById('notificationToast');
            const toast = new bootstrap.Toast(toastEl, {
                delay: 5000
            });
            const toastBody = toastEl.querySelector('.toast-body');

            Livewire.on('show-toast', ({
                message
            }) => {
                if (message) {
                    toastBody.textContent = message;
                    toast.show();
                }
            });
        });
    </script>
    @include('layouts.partials._scripts')
</body>

</html>