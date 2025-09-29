<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::with('settings')->chunk(50, function ($users) {
            foreach ($users as $user) {
                if ($user->settings) {
                    continue;
                }

                $user->settings()->create([
                    'profile_visibility' => fake()->randomElement(['public', 'friends', 'private']),
                    'preferred_locale' => fake()->randomElement(['ar', 'en', null]),
                ]);
            }
        });
    }
}