# Repo Overview

- **Framework**: Laravel 10 (PHP ^8.1)
- **Key Packages**: Livewire ^3.6, Sanctum ^3.3, Tinker, Guzzle
- **Build Tooling**: Vite (vite.config.js), Node (package.json)
- **Auth**: Email verification, Sanctum tokens
- **UI**: Blade + Livewire components

## Domain Summary
- **Users**: `users` table with profile, location (country/city/village), avatar, last_seen
- **RBAC**:
  - Models: `User`, `Role`, `Permission`
  - Pivots: `role_user`, `permission_role`, `permission_user`
  - Policies: UserPolicy (registered), other policies present (City/Country/Village/Role/Permission)
  - Gates: `Gate::before` grants all to `administrator` role
- **Seeders**: Roles (administrator/admin/moderator/user/banned), Permissions (admin routes + CRUD for entities)

## Notable Implementation Details
- User model casts `password` to `hashed`, includes convenience helpers (isOnline, getAvatarUrl, etc.)
- Permission keys follow convention: `browse_*`, `read_*`, `edit_*`, `add_*`, `delete_*`, `restore_*`, `forceDelete_*`
- Livewire user form handles create/update, file upload, and role sync

## Issues Identified (addressed in applied changes)
- Default role detection relied on `name='user'` while seeders define Arabic name; switched to `key='user'`
- Double hashing risk: removed manual `Hash::make` in favor of model cast
- Permissions cache invalidation: added explicit `clearPermissionsCache()` after role sync
- Avatar cleanup: delete old avatar file after successful update
- Validation: tightened avatar mime types and size
- Permission relationship method name fixed to `roles()` for clarity
- Seeder: added missing `manage_roles` permission
- Performance: `hasPermission()` now avoids N+1 via `loadMissing` and uses shorter cache TTL (600s)

## Suggested Next Steps
- Ensure `php artisan storage:link` is executed for avatar URLs
- Register remaining Policies in `AuthServiceProvider` or rely on auto-discovery confidently
- Add authorization checks in Livewire actions if needed (e.g., manageRoles)