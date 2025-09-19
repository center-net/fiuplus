<?php

namespace App\Traits;

use App\Models\City;
use App\Models\Country;
use App\Models\Village;

trait HasLocationLogic
{
    public $countries = [];
    public $cities = [];
    public $villages = [];

    public function loadCountries()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function loadCities($countryId)
    {
        $this->cities = $countryId ? City::where('country_id', $countryId)->orderBy('name')->get() : collect();
    }

    public function loadVillages($cityId)
    {
        $this->villages = $cityId ? Village::where('city_id', $cityId)->orderBy('name')->get() : collect();
    }

    public function updatedCountryId($value)
    {
        $this->loadCities($value);
        $this->city_id = null;
        $this->village_id = null;
        $this->villages = collect();
        $this->resetPageIfExists();
    }

    public function updatedCityId($value)
    {
        $this->loadVillages($value);
        $this->village_id = null;
        $this->resetPageIfExists();
    }

    protected function resetPageIfExists()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}