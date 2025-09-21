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
                @auth
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('images/Flag/' . LaravelLocalization::setLocale() . '.png') }}"
                                class="img-fluid rounded-circle" alt="{{ LaravelLocalization::setLocale() }}"
                                style="height: 30px; min-width: 30px; width: 30px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="messagesDropdown"
                            style="width: 300px;">
                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if ($localeCode != LaravelLocalization::setLocale())
                                    <li>
                                        <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}"
                                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('images/Flag/' . $properties['flag'] . '.png') }}"
                                                        width="32" height="32" class="rounded-circle"
                                                        alt="{{ $properties['flag'] }}">
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="mb-0">{{ $properties['native'] }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <livewire:layout.notifications-dropdown />
                    <livewire:layout.messages-dropdown />

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
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
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">تسجيل الدخول</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">تسجيل</a>
                    </li> --}}
                @endauth
            </ul>
        </div>
    </div>
</nav>
