<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesSlug;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Role;

class Store extends Model implements TranslatableContract
{
    use HasFactory, GeneratesSlug, Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'slug',
        'user_id',
        'logo',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience helper to display
     */
    public function getDisplayName(): string
    {
        return $this->name ?: $this->slug;
    }

    /**
     * Scope search by slug or translated name
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('slug', 'like', "%{$search}%")
              ->orWhereHas('translations', function ($t) use ($search) {
                  $t->where('name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * When a store is deleted, adjust the owner's roles:
     * - If the user has more than one role, remove only the 'merchant' role.
     * - If the user has only the 'merchant' role, switch it to the 'user' role.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Store $store) {
            $user = $store->user; // owner
            if (!$user) {
                return;
            }

            // Ensure roles are loaded once
            $user->loadMissing('roles');

            // Check if the user currently has the merchant role
            $hasMerchant = $user->roles->contains('key', 'merchant');
            if (!$hasMerchant) {
                return; // nothing to change
            }

            // Find needed roles by key
            $merchantRole = Role::where('key', 'merchant')->first();
            $userRole = Role::where('key', 'user')->first();

            if ($user->roles->count() > 1) {
                // Detach only merchant, keep others
                if ($merchantRole) {
                    $user->roles()->detach($merchantRole->id);
                }
            } else {
                // Only one role and it's merchant -> switch to user
                if ($userRole) {
                    $user->roles()->sync([$userRole->id]);
                } else {
                    // Fallback: just remove merchant if 'user' role not found
                    if ($merchantRole) {
                        $user->roles()->detach($merchantRole->id);
                    }
                }
            }

            // Invalidate permissions cache after role change
            $user->clearPermissionsCache();
        });
    }
}