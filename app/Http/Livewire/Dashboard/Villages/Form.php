<?php

namespace App\Http\Livewire\Dashboard\Villages;

use App\Models\Village;
use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Form extends Component
{
    use AuthorizesRequests;

    public $village_id;
    public $name = '';
    public $slug = '';
    public $country_id = '';
    public $city_id = '';

    public $slug_touched = false;

    protected $listeners = ['openVillageFormModal' => 'loadVillage'];

    public function mount()
    {
        $this->authorize('viewAny', Village::class);
        $this->resetForm();
    }

    public function loadVillage($villageId = null)
    {
        $this->resetForm();
        $this->village_id = $villageId;

        if ($villageId) {
            $village = Village::findOrFail($villageId);
            $this->authorize('update', $village);
            $this->name = $village->name; // from translations via accessor
            $this->slug = $village->slug;
            $this->city_id = $village->city_id;
            $this->country_id = optional($village->city)->country_id;
        } else {
            $this->authorize('create', Village::class);
        }
    }

    protected function rules()
    {
        $locale = app()->getLocale();

        return [
            // Validate translated name uniqueness per locale (ignore current village by village_id)
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('village_translations', 'name')
                    ->ignore($this->village_id, 'village_id')
                    ->where(fn($q) => $q->where('locale', $locale))
            ],

            // Slug unique within the same city on base villages table
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('villages', 'slug')
                    ->ignore($this->village_id)
                    ->where(fn($q) => $q->where('city_id', $this->city_id))
            ],

            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
        ];
    }

    private function generateSlug(string $source): string
    {
        $slug = Str::slug($source, '-', 'ar');
        if ($slug === '') {
            $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}]+/u', '-', $source) ?? '';
            $slug = trim($slug, '-');
            $slug = mb_strtolower($slug, 'UTF-8');
        }
        return $slug;
    }

    public function updatedName()
    {
        if (!$this->slug_touched) {
            $this->slug = $this->generateSlug($this->name);
        }
    }

    public function updatedSlug()
    {
        $this->slug_touched = true;
        $this->slug = $this->generateSlug($this->slug);
    }

    public function save()
    {
        $data = $this->validate();
        $locale = app()->getLocale();

        if ($this->village_id) {
            $village = Village::findOrFail($this->village_id);
            $this->authorize('update', $village);
            // Update base fields only
            $village->update([
                'slug' => $data['slug'],
                'city_id' => $this->city_id,
            ]);
            // Save translated name
            $village->translateOrNew($locale)->name = $data['name'];
            $village->save();
            $this->dispatch('show-toast', message: 'تم تحديث القرية بنجاح.');
        } else {
            $this->authorize('create', Village::class);
            // Create base village without name
            $village = Village::create([
                'slug' => $data['slug'],
                'city_id' => $this->city_id,
            ]);
            // Save translated name
            $village->translateOrNew($locale)->name = $data['name'];
            $village->save();
            $this->dispatch('show-toast', message: 'تم اضافة القرية بنجاح.');
        }

        $this->dispatch('villageSaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->village_id = null;
        $this->name = '';
        $this->slug = '';
        $this->country_id = '';
        $this->city_id = '';
        $this->slug_touched = false;
        $this->resetValidation();
    }

    public function updatedCountryId()
    {
        // reset city when country changes
        $this->city_id = '';
    }

    public function render()
    {
        $locale = app()->getLocale();

        $countries = Country::query()
            ->leftJoin('country_translations as cnt', function ($join) use ($locale) {
                $join->on('cnt.country_id', '=', 'countries.id')
                     ->where('cnt.locale', '=', $locale);
            })
            ->select('countries.id', 'cnt.name as name')
            ->orderByRaw('CASE WHEN cnt.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('cnt.name')
            ->get();

        $cities = collect();
        if ($this->country_id) {
            $cities = City::query()
                ->where('country_id', $this->country_id)
                ->leftJoin('city_translations as ct', function ($join) use ($locale) {
                    $join->on('ct.city_id', '=', 'cities.id')
                         ->where('ct.locale', '=', $locale);
                })
                ->select('cities.id', 'ct.name as name')
                ->orderByRaw('CASE WHEN ct.name IS NULL THEN 0 ELSE 1 END')
                ->orderBy('ct.name')
                ->get();
        }

        return view('livewire.dashboard.villages.form', compact('countries','cities'));
    }
}