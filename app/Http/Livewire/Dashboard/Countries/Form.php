<?php

namespace App\Http\Livewire\Dashboard\Countries;

use App\Models\Country;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Form extends Component
{
    use AuthorizesRequests;

    public $country_id;
    public $name = '';
    public $slug = '';
    public $iso3 = '';

    // Track if user manually edited slug to avoid overwriting
    public $slug_touched = false;

    protected $listeners = ['openCountryFormModal' => 'loadCountry'];

    public function mount()
    {
        $this->authorize('viewAny', Country::class);
        $this->resetForm();
    }

    public function loadCountry($countryId = null)
    {
        $this->resetForm();
        $this->country_id = $countryId;

        if ($countryId) {
            $country = Country::findOrFail($countryId);
            $this->authorize('update', $country);
            $this->name = $country->name; // from translations via accessor
            $this->slug = $country->slug;
            $this->iso3 = $country->iso3;
        } else {
            $this->authorize('create', Country::class);
        }
    }

    protected function rules()
    {
        $locale = app()->getLocale();

        return [
            // Validate translated name uniqueness per locale (ignore current country by country_id)
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('country_translations', 'name')
                    ->ignore($this->country_id, 'country_id')
                    ->where(fn($q) => $q->where('locale', $locale))
            ],

            // Slug unique on base countries table
            'slug' => ['required', 'string', 'max:10', Rule::unique('countries', 'slug')->ignore($this->country_id)],

            // ISO3 unique on base countries table
            'iso3' => ['required', 'string', 'size:3', Rule::unique('countries', 'iso3')->ignore($this->country_id)],
        ];
    }

    private function generateSlug(string $source, ?int $max = null): string
    {
        // Try Laravel slug with Arabic locale
        $slug = Str::slug($source, '-', 'ar');

        // Fallback to a Unicode-friendly slug if empty (keeps Arabic letters and numbers)
        if ($slug === '') {
            $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}]+/u', '-', $source) ?? '';
            $slug = trim($slug, '-');
            $slug = mb_strtolower($slug, 'UTF-8');
        }

        if ($max !== null) {
            $slug = Str::limit($slug, $max, '');
        }

        return $slug;
    }

    // Auto-generate slug from name unless user edited slug manually
    public function updatedName()
    {
        if (!$this->slug_touched) {
            $this->slug = $this->generateSlug($this->name, 10); // Countries slug max length 10
        }
    }

    // Normalize and mark slug as manually edited
    public function updatedSlug()
    {
        $this->slug_touched = true;
        $this->slug = $this->generateSlug($this->slug, 10);
    }

    public function save()
    {
        $data = $this->validate();
        $locale = app()->getLocale();

        if ($this->country_id) {
            $country = Country::findOrFail($this->country_id);
            $this->authorize('update', $country);

            // Update base fields only
            $country->update([
                'slug' => $data['slug'],
                'iso3' => $data['iso3'],
            ]);
            // Save translated name
            $country->translateOrNew($locale)->name = $data['name'];
            $country->save();

            $this->dispatch('show-toast', message: 'تم تحديث الدولة بنجاح.');
        } else {
            $this->authorize('create', Country::class);
            // Create base country first (without name)
            $country = Country::create([
                'slug' => $data['slug'],
                'iso3' => $data['iso3'],
            ]);
            // Save translated name
            $country->translateOrNew($locale)->name = $data['name'];
            $country->save();

            $this->dispatch('show-toast', message: 'تم اضافة الدولة بنجاح.');
        }

        $this->dispatch('countrySaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->country_id = null;
        $this->name = '';
        $this->slug = '';
        $this->iso3 = '';
        $this->slug_touched = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.countries.form');
    }
}