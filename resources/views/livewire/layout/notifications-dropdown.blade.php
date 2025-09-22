<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown"
        role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            3
            <span class="visually-hidden">{{ __('app.unread_notifications') }}</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
        aria-labelledby="notificationsDropdown" style="width: 300px;">
        <li>
            <h6 class="dropdown-header">{{ __('app.notifications') }}</h6>
        </li>
        <li><a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-plus text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0">{{ __('app.new_user_registered') }}</p>
                        <small class="text-muted">{{ __('app.minutes_ago', ['minutes' => 5]) }}</small>
                    </div>
                </div>
            </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item text-center" href="#">{{ __('app.view_all_notifications') }}</a></li>
    </ul>
</li>