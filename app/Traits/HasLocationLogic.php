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
        $locale = app()->getLocale();
        $this->countries = Country::select('countries.*')
            ->leftJoin('country_translations as ct', function ($join) use ($locale) {
                $join->on('ct.country_id', '=', 'countries.id')
                    ->where('ct.locale', '=', $locale);
            })
            ->orderBy('ct.name')
            ->get();
    }

    public function loadCities($countryId)
    {
        if (!$countryId) {
            $this->cities = collect();
            return;
        }
        $locale = app()->getLocale();
        $this->cities = City::select('cities.*')
            ->where('country_id', $countryId)
            ->leftJoin('city_translations as ct', function ($join) use ($locale) {
                $join->on('ct.city_id', '=', 'cities.id')
                    ->where('ct.locale', '=', $locale);
            })
            ->orderBy('ct.name')
            ->get();
    }

    public function loadVillages($cityId)
    {
        if (!$cityId) {
            $this->villages = collect();
            return;
        }
        $locale = app()->getLocale();
        $this->villages = Village::select('villages.*')
            ->where('city_id', $cityId)
            ->leftJoin('village_translations as vt', function ($join) use ($locale) {
                $join->on('vt.village_id', '=', 'villages.id')
                    ->where('vt.locale', '=', $locale);
            })
            ->orderBy('vt.name')
            ->get();
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