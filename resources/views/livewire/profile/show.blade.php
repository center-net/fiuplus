<div class="container py-4 fb-profile-page">
    <header class="card border-0 shadow-sm overflow-hidden mb-4 fb-profile-cover">
        <div class="fb-cover-photo position-relative">
            <img src="{{ $profileUser->cover_photo_url ?? asset('images/profile/default-cover.jpg') }}" alt="{{ $profileUser->name }} cover image" class="img-fluid w-100 object-fit-cover" style="height: 280px; object-fit: cover;">
        </div>
        <div class="card-body pt-0">
            <div class="row g-4 align-items-end fb-profile-header">
                <div class="col-md-auto text-center text-md-start">
                    <div class="d-inline-block position-relative" style="margin-top: -84px;">
                        <img src="{{ $profileUser->getAvatarUrl() }}" alt="{{ $profileUser->name }} avatar" width="168" height="168" class="rounded-circle border border-4 border-white shadow-lg fb-profile-avatar-img" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div class="fb-profile-info">
                            <h1 class="h2 mb-1">{{ $profileUser->name }}</h1>
                            <p class="text-muted mb-3">{{ '@' . ($profileUser->username ?? Str::slug($profileUser->name)) }}</p>
                            <div class="d-flex flex-wrap text-muted gap-3 fb-profile-meta">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-primary" aria-hidden="true"></i>
                                    {{ $profileUser->city->name ?? __('profile.location_not_set') }}
                                </span>
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-briefcase text-primary" aria-hidden="true"></i>
                                    {{ $profileUser->job_title ?? __('profile.job_not_set') }}
                                </span>
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fas fa-graduation-cap text-primary" aria-hidden="true"></i>
                                    {{ $profileUser->education ?? __('profile.education_not_set') }}
                                </span>
                            </div>
                        </div>
                        <div class="fb-profile-actions d-flex flex-wrap gap-2">
                            @if ($canEditProfile)
                                <button class="btn btn-primary d-inline-flex align-items-center gap-2" type="button">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                    {{ __('profile.edit_profile_button') }}
                                </button>
                            @else
                                <button class="btn btn-outline-primary d-inline-flex align-items-center gap-2" type="button">
                                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                                    {{ __('profile.add_friend_button') }}
                                </button>
                                <button class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" type="button">
                                    <i class="fas fa-comment" aria-hidden="true"></i>
                                    {{ __('profile.message_button') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="d-flex flex-column flex-lg-row gap-4 fb-profile-body">
        <aside class="col-lg-4 col-xl-3 order-2 order-lg-1 fb-profile-sidebar d-none d-lg-block" aria-label="{{ __('profile.sidebar_about_label') }}">
            <section class="card shadow-sm mb-4 fb-card">
                <div class="card-header bg-white border-0 pb-0 fb-card-header">
                    <h2 class="h5 mb-0">{{ __('profile.about_title') }}</h2>
                </div>
                <div class="card-body fb-card-body">
                    <ul class="list-unstyled mb-0 fb-about-list">
                        <li class="mb-3">
                            <strong class="d-block text-uppercase text-secondary small">{{ __('profile.about_bio') }}</strong>
                            <p class="mb-0">{{ $profileUser->bio ?? __('profile.bio_not_set') }}</p>
                        </li>
                        <li class="mb-3">
                            <strong class="d-block text-uppercase text-secondary small">{{ __('profile.about_work') }}</strong>
                            <p class="mb-0">{{ $profileUser->job_title ?? __('profile.job_not_set') }}</p>
                        </li>
                        <li class="mb-3">
                            <strong class="d-block text-uppercase text-secondary small">{{ __('profile.about_education') }}</strong>
                            <p class="mb-0">{{ $profileUser->education ?? __('profile.education_not_set') }}</p>
                        </li>
                        <li class="mb-3">
                            <strong class="d-block text-uppercase text-secondary small">{{ __('profile.about_location') }}</strong>
                            <p class="mb-0">{{ $profileUser->city->name ?? __('profile.location_not_set') }}</p>
                        </li>
                        <li class="mb-0">
                            <strong class="d-block text-uppercase text-secondary small">{{ __('profile.about_contact') }}</strong>
                            <p class="mb-0">{{ $profileUser->phone ?? __('profile.contact_not_set') }}</p>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="card shadow-sm fb-card">
                <div class="card-header bg-white border-0 pb-0 fb-card-header">
                    <h2 class="h5 mb-0">{{ __('profile.privacy_settings_title') }}</h2>
                </div>
                <div class="card-body fb-card-body">
                    @if ($canEditProfile)
                        <div class="fb-privacy-selector">
                            <label for="profilePrivacy" class="form-label visually-hidden">{{ __('profile.privacy_selector_label') }}</label>
                            <select id="profilePrivacy" wire:change="updatePrivacySetting($event.target.value)" aria-label="{{ __('profile.privacy_selector_label') }}" class="form-select">
                                @foreach ($privacyOptions as $key => $label)
                                    <option value="{{ $key }}" @selected($privacySetting === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <p class="fb-privacy-info mb-0 text-muted d-flex align-items-center gap-2">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            {{ __('profile.privacy_viewer_text.' . $privacySetting) }}
                        </p>
                    @endif
                </div>
            </section>
        </aside>

        <main class="col-12 col-lg order-1 order-lg-2 fb-profile-main" aria-label="{{ __('profile.timeline_label') }}">
            <nav class="nav nav-pills gap-2 mb-4 fb-profile-tabs" role="tablist">
                <button class="nav-link active" type="button">{{ __('profile.tab_timeline') }}</button>
                <button class="nav-link" type="button">{{ __('profile.tab_about') }}</button>
                <button class="nav-link" type="button">{{ __('profile.tab_friends') }}</button>
                <button class="nav-link" type="button">{{ __('profile.tab_photos') }}</button>
            </nav>

            @if (! $canView)
                <div class="card shadow-sm fb-empty-state text-center text-muted">
                    <div class="card-body py-5">
                        <i class="fas fa-lock fa-2x mb-3" aria-hidden="true"></i>
                        <h2 class="h5">{{ __('profile.restricted_title') }}</h2>
                        <p class="mb-0">{{ __('profile.restricted_description.' . $privacySetting) }}</p>
                    </div>
                </div>
            @else
                <section aria-label="{{ __('profile.create_post_label') }}" class="card shadow-sm mb-4 fb-card">
                    <div class="card-body">
                        <textarea class="form-control mb-3" rows="3" placeholder="{{ __('profile.create_post_placeholder') }}" disabled></textarea>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" disabled>
                                <i class="fas fa-video" aria-hidden="true"></i>
                                {{ __('profile.create_post_live_video') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" disabled>
                                <i class="fas fa-images" aria-hidden="true"></i>
                                {{ __('profile.create_post_photo_video') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" disabled>
                                <i class="fas fa-smile" aria-hidden="true"></i>
                                {{ __('profile.create_post_feeling') }}
                            </button>
                        </div>
                    </div>
                </section>

                <section aria-label="{{ __('profile.timeline_posts_label') }}" class="card shadow-sm fb-card">
                    <div class="card-body">
                        @forelse ($posts as $post)
                            <article class="fb-post {{ ! $loop->last ? 'pb-4 mb-4 border-bottom' : '' }}">
                                <header class="d-flex justify-content-between align-items-start mb-3 fb-post-header">
                                    <div class="d-flex align-items-center gap-3 fb-post-author">
                                        <img src="{{ $profileUser->getAvatarUrl() }}" alt="{{ $profileUser->name }}" width="48" height="48" class="rounded-circle border" style="object-fit: cover;">
                                        <div>
                                            <h3 class="h6 mb-1">{{ $profileUser->name }}</h3>
                                            <time datetime="{{ $post->created_at }}" class="text-muted small">{{ $post->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-link text-secondary p-0 fb-btn-icon" aria-label="{{ __('profile.post_more_options') }}">
                                        <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                </header>
                                <div class="fb-post-content mb-3">
                                    <p class="mb-0">{{ $post->content }}</p>
                                    @if ($post->media_url)
                                        <img src="{{ $post->media_url }}" alt="" class="img-fluid rounded mt-3 fb-post-media">
                                    @endif
                                </div>
                                <footer class="fb-post-footer">
                                    <div class="d-flex flex-wrap gap-3 fb-post-actions">
                                        <button type="button" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 text-muted">
                                            <i class="far fa-thumbs-up" aria-hidden="true"></i>{{ __('profile.post_like') }}
                                        </button>
                                        <button type="button" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 text-muted">
                                            <i class="far fa-comment" aria-hidden="true"></i>{{ __('profile.post_comment') }}
                                        </button>
                                        <button type="button" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-2 text-muted">
                                            <i class="far fa-share-square" aria-hidden="true"></i>{{ __('profile.post_share') }}
                                        </button>
                                    </div>
                                </footer>
                            </article>
                        @empty
                            <div class="fb-empty-state text-center text-muted py-5">
                                <i class="fas fa-inbox fa-2x mb-3" aria-hidden="true"></i>
                                <h2 class="h5">{{ __('profile.no_posts_title') }}</h2>
                                <p class="mb-0">{{ __('profile.no_posts_description') }}</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            @endif
        </main>
    </div>
</div>