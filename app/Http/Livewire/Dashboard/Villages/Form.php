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
            $this->name = $village->name;
            $this->slug = $village->slug;
            $this->city_id = $village->city_id;
            $this->country_id = optional($village->city)->country_id;
        } else {
            $this->authorize('create', Village::class);
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('villages')->ignore($this->village_id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('villages')->ignore($this->village_id)->where(fn($q) => $q->where('city_id', $this->city_id))],
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

        if ($this->village_id) {
            $village = Village::findOrFail($this->village_id);
            $this->authorize('update', $village);
            $village->update($data);
            $this->dispatch('show-toast', message: 'تم تحديث القرية بنجاح.');
        } else {
            $this->authorize('create', Village::class);
            Village::create($data);
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
        $countries = Country::orderBy('name')->get(['id','name']);
        $cities = [];
        if ($this->country_id) {
            $cities = City::where('country_id', $this->country_id)
                ->orderBy('name')
                ->get(['id','name']);
        }
        return view('livewire.dashboard.villages.form', compact('countries','cities'));
    }
}