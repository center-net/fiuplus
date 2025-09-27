<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    @include('layouts.partials._head')
</head>

<body>
    @php($user = auth()->user())
    <div class="fb-app">
        <header class="fb-topbar">
            <div class="fb-logo">
                <i class="fas fa-bolt"></i>
                <a href="{{ url('/') }}">FiuPlus</a>
            </div>

            <div class="fb-search">
                <i class="fas fa-search"></i>
                <input type="search" placeholder="{{ __('Search on FiuPlus') }}"
                    aria-label="{{ __('Search on FiuPlus') }}">
            </div>

            <nav class="fb-nav" aria-label="{{ __('Primary navigation') }}">
                <a href="#" class="active"><i class="fas fa-home fa-lg"></i><span>{{ __('Home') }}</span></a>
                <a href="#"><i class="fas fa-users fa-lg"></i><span>{{ __('Friends') }}</span></a>
                <a href="#"><i class="fas fa-tv fa-lg"></i><span>{{ __('Watch') }}</span></a>
                <a href="#"><i class="fas fa-store fa-lg"></i><span>{{ __('Marketplace') }}</span></a>
                <a href="#"><i class="fas fa-gamepad fa-lg"></i><span>{{ __('Gaming') }}</span></a>
            </nav>

            <div class="fb-quick-actions">
                <button class="fb-action-btn" type="button" aria-label="{{ __('Create') }}"><i
                        class="fas fa-plus"></i></button>
                <button class="fb-action-btn" type="button" aria-label="{{ __('Messenger') }}"><i
                        class="fab fa-facebook-messenger"></i></button>
                <button class="fb-action-btn" type="button" aria-label="{{ __('Notifications') }}"><i
                        class="fas fa-bell"></i></button>
                <div class="fb-language-switcher">

                    <label for="localeSwitcher" class="visually-hidden"> {{ LaravelLocalization::setLocale() }}</label>
                    <div id="localeSwitcher" onchange="if(this.value) window.location.href=this.value;">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if ($localeCode != LaravelLocalization::setLocale())
                                
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
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="fb-profile-mini">
                    <div class="fb-avatar">{{ $user ? mb_substr($user->name, 0, 1) : __('G') }}</div>
                    <span>{{ $user->name ?? __('Guest') }}</span>
                </div>
            </div>
        </header>

        <main class="fb-layout">
            <aside class="fb-column fb-column-left" aria-label="{{ __('Left navigation') }}">
                <article class="fb-card">
                    <h2>{{ __('Menu') }}</h2>
                    <ul class="fb-menu-list">
                        <li class="fb-menu-item"><i class="fas fa-user"></i><span>{{ __('Profile') }}</span></li>
                        <li class="fb-menu-item"><i
                                class="fas fa-satellite-dish"></i><span>{{ __('News Feed') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-calendar-alt"></i><span>{{ __('Events') }}</span>
                        </li>
                        <li class="fb-menu-item"><i class="fas fa-flag"></i><span>{{ __('Pages') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-bookmark"></i><span>{{ __('Saved') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-people-carry"></i><span>{{ __('Groups') }}</span>
                        </li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('Shortcuts') }}</h2>
                    <ul class="fb-groups-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-laptop-code"></i><span>{{ __('Developers Community') }}</span></li>
                        <li class="fb-menu-item"><i
                                class="fas fa-plane"></i><span>{{ __('Travel Buddies 2024') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-dumbbell"></i><span>{{ __('Fitness Goals') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-paint-brush"></i><span>{{ __('Creative Designers Hub') }}</span></li>
                    </ul>
                </article>
            </aside>

            <section class="fb-column fb-column-center" aria-label="{{ __('Main content') }}">
                <article class="fb-card">
                    <h2>{{ __('Stories') }}</h2>
                    <div class="fb-story-list">
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/300x400?nature');">
                            <span>{{ __('Lina Adventures') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/301x401?city');">
                            <span>{{ __('Tech Friday') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/302x402?travel');">
                            <span>{{ __('Team Retreat') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/303x403?food');">
                            <span>{{ __('Chef Karim') }}</span>
                        </div>
                    </div>
                </article>

                <div class="fb-card">

                    <div class="fb-post-list">

                        {{ $slot }}
                    </div>
                </div>
            </section>

            <aside class="fb-column fb-column-right" aria-label="{{ __('Right sidebar') }}">
                <article class="fb-card">
                    <h2>{{ __('Upcoming Events') }}</h2>
                    <ul class="fb-menu-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-code"></i><span>{{ __('Hackathon Weekend · 2 days') }}</span></li>
                        <li class="fb-menu-item"><i
                                class="fas fa-chalkboard-teacher"></i><span>{{ __('UI Masterclass · Online') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-hiking"></i><span>{{ __('Community Hike · Saturday') }}</span></li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('Suggested Pages') }}</h2>
                    <ul class="fb-groups-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-lightbulb"></i><span>{{ __('Startup Sparks') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-heart"></i><span>{{ __('Wellness Daily') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-camera-retro"></i><span>{{ __('Lens Lovers') }}</span></li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('Contacts') }}</h2>
                    <ul class="fb-contact-list">
                        <li><span>{{ __('Omar Ali') }}</span><span class="fb-contact-status"></span></li>
                        <li><span>{{ __('Maria Chen') }}</span><span class="fb-contact-status"></span></li>
                        <li><span>{{ __('Khaled Noor') }}</span><span class="fb-contact-status"></span></li>
                        <li><span>{{ __('Isabella Rossi') }}</span><span class="fb-contact-status"></span></li>
                    </ul>
                </article>
            </aside>
        </main>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const toastEl = document.getElementById('notificationToast');
            if (!toastEl) {
                return;
            }

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
