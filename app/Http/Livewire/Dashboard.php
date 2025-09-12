<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\Village;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'stats' => [
                'users' => User::count(),
                'countries' => Country::count(),
                'cities' => City::count(),
                'villages' => Village::count(),
            ]
        ]);
    }
}
