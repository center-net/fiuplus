<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::firstOrCreate([
            'name' => 'دولة فلسطين',
            'slug' => 'PS',
            'iso3' => 'PSE',
        ]);
    }
}
