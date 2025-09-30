<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Show extends Component
{
    /**
     * The user whose profile is being displayed.
     */
    public User $profileUser;

    /**
     * Collection of posts to show in the timeline.
     */
    public Collection $posts;

    /**
     * Viewer relationship to the profile owner.
     */
    public ?string $viewerRelationship = null;

    /**
     * Privacy option selected by the profile owner.
     */
    public string $privacySetting = 'public';

    /**
     * Whether the currently authenticated user is allowed to view the profile.
     */
    public bool $canView = false;

    /**
     * Privacy options map.
     */
    public array $privacyOptions = [];

    /**
     * Livewire listeners.
     */
    protected $listeners = ['profileUpdated' => 'refreshProfile'];

    /**
     * Mount the component with a resolved user.
     */
    public function mount(?User $user = null): void
    {
        $viewer = auth()->user();
        $this->profileUser = $user ?? $viewer ?? abort(404);
        $this->profileUser->loadMissing(['profile.translations', 'settings']);

        $this->loadPrivacyOptions();
        $this->privacySetting = $this->profileUser->profile_visibility ?? 'public';
        $this->viewerRelationship = $this->determineRelationship($viewer);
        $this->canView = $this->checkAccess($viewer);

        if (! $this->canView) {
            return;
        }

        $this->posts = $this->loadPosts();
    }

    /**
     * Update privacy setting for the profile owner.
     */
    public function updatePrivacySetting(string $option): void
    {
        abort_unless(auth()->id() === $this->profileUser->id, 403);

        if (! array_key_exists($option, $this->privacyOptions)) {
            return;
        }

        $settings = $this->profileUser->settings()->updateOrCreate([], [
            'profile_visibility' => $option,
            'preferred_locale' => $this->profileUser->settings?->preferred_locale,
        ]);

        $this->profileUser->setRelation('settings', $settings);
        $this->privacySetting = $option;
        $this->canView = $this->checkAccess(auth()->user());
    }

    /**
     * Determine relationship between viewer and profile owner.
     */
    protected function determineRelationship(?User $viewer): ?string
    {
        if (! $viewer) {
            return null;
        }

        if ($viewer->id === $this->profileUser->id) {
            return 'self';
        }

        // Check if viewer is a friend of the profile user
        return $this->profileUser->friends->contains($viewer->id) ? 'friend' : 'other';
    }

    /**
     * Check whether the viewer can access the profile.
     */
    protected function checkAccess(?User $viewer): bool
    {
        return match ($this->privacySetting) {
            'public' => true,
            'friends' => $this->viewerRelationship === 'friend' || $this->viewerRelationship === 'self',
            'private' => $this->viewerRelationship === 'self',
            default => false,
        };
    }

    /**
     * Load posts timeline.
     */
    protected function loadPosts(): Collection
    {
        // TODO: replace with real posts retrieval logic once available.
        return new Collection();
    }

    /**
     * Prepare privacy options list.
     */
    protected function loadPrivacyOptions(): void
    {
        $this->privacyOptions = [
            'public' => __('profile.privacy_public'),
            'friends' => __('profile.privacy_friends'),
            'private' => __('profile.privacy_private'),
        ];
    }

    #[Computed]
    public function canEditProfile(): bool
    {
        return auth()->id() === $this->profileUser->id || Gate::allows('update', $this->profileUser);
    }

    /**
     * Refresh profile data after update.
     */
    public function refreshProfile(): void
    {
        $this->profileUser->refresh();
        $this->profileUser->loadMissing(['profile.translations', 'settings']);
        $this->privacySetting = $this->profileUser->profile_visibility ?? 'public';
    }

    public function render()
    {
        return view('livewire.profile.show', [
            'canEditProfile' => $this->canEditProfile(),
        ])
        ->layout('layouts.app', ['title' => __('app.store_categories_manage_title')]);
    }
}