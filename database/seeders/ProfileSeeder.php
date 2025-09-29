<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultFaker = FakerFactory::create();
        $englishFaker = FakerFactory::create('en_US');
        $arabicFaker = FakerFactory::create('ar_SA');

        User::with('profile')->chunk(50, function ($users) use ($defaultFaker, $englishFaker, $arabicFaker) {
            foreach ($users as $user) {
                if ($user->profile) {
                    continue;
                }

                $dateOfBirth = $defaultFaker->optional()->dateTimeBetween('-48 years', '-18 years');

                /** @var \App\Models\Profile $profile */
                $profile = $user->profile()->create([
                    'cover_photo' => null,
                    'date_of_birth' => $dateOfBirth ? Carbon::parse($dateOfBirth) : null,
                ]);

                $profile->translateOrNew('en')->bio = $englishFaker->paragraph();
                $profile->translateOrNew('en')->job_title = $englishFaker->jobTitle();
                $profile->translateOrNew('en')->education = $englishFaker->company() . ' - ' . $englishFaker->catchPhrase();

                $profile->translateOrNew('ar')->bio = $arabicFaker->paragraph();
                $profile->translateOrNew('ar')->job_title = $arabicFaker->jobTitle();
                $profile->translateOrNew('ar')->education = $arabicFaker->company();

                $profile->save();
            }
        });
    }
}