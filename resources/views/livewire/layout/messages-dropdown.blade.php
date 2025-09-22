<li class="nav-item dropdown mx-2">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown"
        role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-envelope"></i>
        <span
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            2
            <span class="visually-hidden">{{ __('app.unread_messages') }}</span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
        aria-labelledby="messagesDropdown" style="width: 300px;">
        <li>
            <h6 class="dropdown-header">{{ __('app.messages') }}</h6>
        </li>
        <li><a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="https://github.com/mdo.png" width="32" height="32"
                            class="rounded-circle" alt="{{ __('app.user_image_alt') }}">
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0">{{ __('app.sample_user_name') }}</p>
                        <small class="text-muted">{{ __('app.sample_message_greeting') }}</small>
                    </div>
                </div>
            </a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item text-center" href="#">{{ __('app.view_all_messages') }}</a></li>
    </ul>
</li>