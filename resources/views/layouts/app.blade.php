<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    @include('layouts.partials._head')
</head>

<body>
    @php
        $user = auth()->user();
        $showRightColumn = request()->routeIs('admin.dashboard');
    @endphp
    <div class="fb-app">
        <header class="fb-topbar">
            <div class="fb-logo">
                <i class="fas fa-bolt"></i>
                <a href="{{ url('/') }}">{{ __('layout.brand_name') }}</a>
            </div>

            <div class="fb-search" role="search">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input type="search" placeholder="{{ __('layout.search_placeholder') }}"
                    aria-label="{{ __('layout.search_placeholder') }}">
            </div>

            <nav class="fb-nav" aria-label="{{ __('layout.primary_nav_label') }}">
                <a href="#" class="active" aria-label="{{ __('layout.nav_home_label') }}"><i class="fas fa-home fa-lg" aria-hidden="true"></i><span>{{ __('layout.nav_home') }}</span></a>
                <a href="#" aria-label="{{ __('layout.nav_friends_label') }}"><i class="fas fa-users fa-lg" aria-hidden="true"></i><span>{{ __('layout.nav_friends') }}</span></a>
                <a href="#" aria-label="{{ __('layout.nav_watch_label') }}"><i class="fas fa-tv fa-lg" aria-hidden="true"></i><span>{{ __('layout.nav_watch') }}</span></a>
                <a href="#" aria-label="{{ __('layout.nav_marketplace_label') }}"><i class="fas fa-store fa-lg" aria-hidden="true"></i><span>{{ __('layout.nav_marketplace') }}</span></a>
                <a href="#" aria-label="{{ __('layout.nav_gaming_label') }}"><i class="fas fa-gamepad fa-lg" aria-hidden="true"></i><span>{{ __('layout.nav_gaming') }}</span></a>
            </nav>

            <div class="fb-quick-actions">
                <button class="fb-action-btn" type="button" aria-label="{{ __('layout.action_create') }}" title="{{ __('layout.action_create') }}"><i
                        class="fas fa-plus" aria-hidden="true"></i></button>
                <button class="fb-action-btn" type="button" aria-label="{{ __('layout.action_messenger') }}" title="{{ __('layout.action_messenger') }}"><i
                        class="fab fa-facebook-messenger" aria-hidden="true"></i></button>
                <button class="fb-action-btn" type="button" aria-label="{{ __('layout.action_notifications') }}" title="{{ __('layout.action_notifications') }}"><i
                        class="fas fa-bell" aria-hidden="true"></i></button>
                <div class="fb-language-switcher" aria-label="{{ __('layout.language_switcher_label') }}">

                    <label for="localeSwitcher" class="visually-hidden">{{ __('layout.language_switcher_label') }}</label>
                    <div id="localeSwitcher" onchange="if(this.value) window.location.href=this.value;">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if ($localeCode != LaravelLocalization::setLocale())
                                <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('images/Flag/' . $properties['flag'] . '.png') }}"
                                                width="32" height="32" class="rounded-circle"
                                                alt="{{ __('layout.locale_flag_alt', ['locale' => $properties['native']]) }}">
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
                <div class="fb-profile-menu" data-profile-menu>
                    <button class="fb-profile-mini" type="button" aria-label="{{ __('layout.profile_menu_label') }}" aria-haspopup="true"
                        aria-expanded="false" aria-controls="profileMenuDropdown">
                        <div class="fb-avatar" aria-hidden="true">
                            @if ($user && $user->getAvatarUrl())
                                <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" width="32" height="32"
                                    class="rounded-circle">
                            @else
                                <span>{{ $user ? mb_substr($user->name, 0, 1) : __('layout.user_guest_initial') }}</span>
                            @endif
                        </div>
                        <span>{{ $user->name ?? __('layout.user_guest') }}</span>
                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <ul id="profileMenuDropdown" class="fb-profile-dropdown" role="menu" hidden>
                        <li role="none"><a role="menuitem" href="{{ $user ? route('profile.show', $user->username) : route('login') }}">{{ __('layout.profile_menu_profile') }}</a></li>
                        <li role="none"><a role="menuitem" href="{{ url('/settings') }}">{{ __('layout.profile_menu_settings') }}</a></li>
                        <li role="none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">{{ __('layout.profile_menu_logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="fb-layout {{ $showRightColumn ? '' : 'fb-layout--no-right' }}">
            <aside class="fb-column fb-column-left" aria-label="{{ __('layout.left_nav_label') }}">
                <article class="fb-card">
                    <h2>{{ __('layout.left_menu_title') }}</h2>
                    <ul class="fb-menu-list">
                        <li class="fb-menu-item"><i class="fas fa-user" aria-hidden="true"></i><span>{{ __('layout.menu_profile') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-satellite-dish" aria-hidden="true"></i><span>{{ __('layout.menu_news_feed') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-calendar-alt" aria-hidden="true"></i><span>{{ __('layout.menu_events') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-flag" aria-hidden="true"></i><span>{{ __('layout.menu_pages') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-bookmark" aria-hidden="true"></i><span>{{ __('layout.menu_saved') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-users" aria-hidden="true"></i><span>{{ __('layout.menu_groups') }}</span></li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('layout.shortcuts_title') }}</h2>
                    <ul class="fb-groups-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-laptop-code" aria-hidden="true"></i><span>{{ __('layout.shortcut_developers_community') }}</span></li>
                        <li class="fb-menu-item"><i
                                class="fas fa-plane" aria-hidden="true"></i><span>{{ __('layout.shortcut_travel_buddies') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-dumbbell" aria-hidden="true"></i><span>{{ __('layout.shortcut_fitness_goals') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-paint-brush" aria-hidden="true"></i><span>{{ __('layout.shortcut_creative_designers') }}</span></li>
                    </ul>
                </article>
            </aside>

            <section class="fb-column fb-column-center" aria-label="{{ __('layout.main_content_label') }}">
                <article class="fb-card">
                    <h2>{{ __('layout.stories_title') }}</h2>
                    <div class="fb-story-list">
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/300x400?nature');">
                            <span>{{ __('layout.story_lina_adventures') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/301x401?city');">
                            <span>{{ __('layout.story_tech_friday') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/302x402?travel');">
                            <span>{{ __('layout.story_team_retreat') }}</span>
                        </div>
                        <div class="fb-story"
                            style="background-image: url('https://source.unsplash.com/random/303x403?food');">
                            <span>{{ __('layout.story_chef_karim') }}</span>
                        </div>
                    </div>
                </article>

                <div class="fb-card" aria-label="{{ __('layout.post_list_label') }}">

                    <div class="fb-post-list">

                        {{ $slot }}
                    </div>
                </div>
            </section>

            <aside class="fb-column fb-column-right {{ $showRightColumn ? '' : 'd-none' }}" aria-label="{{ __('layout.right_sidebar_label') }}">
                <article class="fb-card">
                    <h2>{{ __('layout.events_title') }}</h2>
                    <ul class="fb-menu-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-code" aria-hidden="true"></i><span>{{ __('layout.event_hackathon') }}</span></li>
                        <li class="fb-menu-item"><i
                                class="fas fa-chalkboard-teacher" aria-hidden="true"></i><span>{{ __('layout.event_ui_masterclass') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-hiking" aria-hidden="true"></i><span>{{ __('layout.event_community_hike') }}</span></li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('layout.suggested_pages_title') }}</h2>
                    <ul class="fb-groups-list">
                        <li class="fb-menu-item"><i
                                class="fas fa-lightbulb" aria-hidden="true"></i><span>{{ __('layout.page_startup_sparks') }}</span></li>
                        <li class="fb-menu-item"><i class="fas fa-heart" aria-hidden="true"></i><span>{{ __('layout.page_wellness_daily') }}</span>
                        </li>
                        <li class="fb-menu-item"><i
                                class="fas fa-camera-retro" aria-hidden="true"></i><span>{{ __('layout.page_lens_lovers') }}</span></li>
                    </ul>
                </article>

                <article class="fb-card">
                    <h2>{{ __('layout.contacts_title') }}</h2>
                    <ul class="fb-contact-list">
                        <li><span>{{ __('layout.contact_omar_ali') }}</span><span class="fb-contact-status" aria-label="{{ __('layout.contact_status_online') }}"></span></li>
                        <li><span>{{ __('layout.contact_maria_chen') }}</span><span class="fb-contact-status" aria-label="{{ __('layout.contact_status_online') }}"></span></li>
                        <li><span>{{ __('layout.contact_khaled_noor') }}</span><span class="fb-contact-status" aria-label="{{ __('layout.contact_status_online') }}"></span></li>
                        <li><span>{{ __('layout.contact_isabella_rossi') }}</span><span class="fb-contact-status" aria-label="{{ __('layout.contact_status_online') }}"></span></li>
                    </ul>
                </article>
            </aside>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileMenu = document.querySelector('[data-profile-menu]');
            if (!profileMenu) {
                return;
            }

            const toggleButton = profileMenu.querySelector('.fb-profile-mini');
            const dropdown = profileMenu.querySelector('.fb-profile-dropdown');
            const focusableSelector = 'a[href], button:not([disabled])';

            const closeMenu = () => {
                if (!profileMenu.classList.contains('open')) {
                    return;
                }

                profileMenu.classList.remove('open');
                toggleButton.setAttribute('aria-expanded', 'false');
                dropdown.hidden = true;
            };

            const openMenu = () => {
                if (profileMenu.classList.contains('open')) {
                    return;
                }

                profileMenu.classList.add('open');
                toggleButton.setAttribute('aria-expanded', 'true');
                dropdown.hidden = false;
            };

            const toggleMenu = () => {
                if (profileMenu.classList.contains('open')) {
                    closeMenu();
                    return;
                }

                openMenu();
                const [firstItem] = dropdown.querySelectorAll(focusableSelector);
                if (firstItem) {
                    firstItem.focus({ preventScroll: true });
                }
            };

            toggleButton.addEventListener('click', (event) => {
                event.preventDefault();
                toggleMenu();
            });

            toggleButton.addEventListener('keydown', (event) => {
                const { key } = event;
                if (key === 'Enter' || key === ' ') {
                    event.preventDefault();
                    toggleMenu();
                } else if (key === 'ArrowDown') {
                    event.preventDefault();
                    openMenu();
                    const [firstItem] = dropdown.querySelectorAll(focusableSelector);
                    if (firstItem) {
                        firstItem.focus({ preventScroll: true });
                    }
                } else if (key === 'Escape') {
                    event.preventDefault();
                    closeMenu();
                }
            });

            dropdown.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeMenu();
                    toggleButton.focus({ preventScroll: true });
                }
            });

            document.addEventListener('click', (event) => {
                if (!profileMenu.contains(event.target)) {
                    closeMenu();
                }
            });

            profileMenu.addEventListener('focusout', (event) => {
                const nextFocused = event.relatedTarget;
                if (!nextFocused || !profileMenu.contains(nextFocused)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        });
    </script>

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
