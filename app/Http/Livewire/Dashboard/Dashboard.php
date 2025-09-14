<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\Village;
use Livewire\Component;

class Dashboard extends Component
{
    public $usersCount;
    public $countriesCount;
    public $citiesCount;
    public $villagesCount;

    public function mount()
    {
        $this->usersCount = User::count();
        $this->countriesCount = Country::count();
        $this->citiesCount = City::count();
        $this->villagesCount = Village::count();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')
            ->layout('layouts.app', ['title' => 'لوحة التحكم']);
    }
}