<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'username' => Str::slug($name) . random_int(100, 999),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+970' . fake()->numberBetween(56, 59) . fake()->numberBetween(1000000, 9999999),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'avatar' => 'images/users/default.png',
            'last_seen' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\User $user) {
            // Attach the default 'user' role unless other roles are specified
            if ($user->roles->isEmpty()) {
                $userRole = Role::where('key', 'user')->first();
                if ($userRole) {
                    $user->roles()->attach($userRole);
                }
            }
        });
    }

    /**
     * Indicate that the user should have a specific role.
     *
     * @param string $roleKey
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRole(string $roleKey)
    {
        return $this->afterCreating(function (\App\Models\User $user) use ($roleKey) {
            $role = Role::where('key', $roleKey)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        });
    }
}