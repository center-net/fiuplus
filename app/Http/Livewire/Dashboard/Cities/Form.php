<?php

namespace App\Http\Livewire\Dashboard\Cities;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Form extends Component
{
    use AuthorizesRequests;

    public $city_id;
    public $name = '';
    public $slug = '';
    public $country_id = '';
    public $delivery_cost = '0.00';

    public $slug_touched = false;

    protected $listeners = ['openCityFormModal' => 'loadCity'];

    public function mount()
    {
        $this->authorize('viewAny', City::class);
        $this->resetForm();
    }

    public function loadCity($cityId = null)
    {
        $this->resetForm();
        $this->city_id = $cityId;

        if ($cityId) {
            $city = City::findOrFail($cityId);
            $this->authorize('update', $city);
            $this->name = $city->name;
            $this->slug = $city->slug;
            $this->country_id = $city->country_id;
            $this->delivery_cost = (string) ($city->delivery_cost ?? '0.00');
        } else {
            $this->authorize('create', City::class);
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cities')->ignore($this->city_id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('cities')->ignore($this->city_id)->where(fn($q) => $q->where('country_id', $this->country_id))],
            'country_id' => ['required', 'exists:countries,id'],
            'delivery_cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
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

        if ($this->city_id) {
            $city = City::findOrFail($this->city_id);
            $this->authorize('update', $city);
            $city->update($data);
            $city->refresh();
            $this->dispatch('show-toast', message: 'تم تحديث المدينة بنجاح.');
        } else {
            $this->authorize('create', City::class);
            City::create($data);
            $this->dispatch('show-toast', message: 'تم اضافة المدينة بنجاح.');
        }

        $this->dispatch('citySaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->city_id = null;
        $this->name = '';
        $this->slug = '';
        $this->country_id = '';
        $this->delivery_cost = '0.00';
        $this->slug_touched = false;
        $this->resetValidation();
    }

    public function render()
    {
        $countries = Country::orderBy('name')->get(['id','name']);
        return view('livewire.dashboard.cities.form', compact('countries'));
    }
}