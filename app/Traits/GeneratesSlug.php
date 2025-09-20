<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Shared slug generation with Arabic support and optional length limit.
 */
trait GeneratesSlug
{
    /**
     * Allow models to override max length via method instead of property.
     */
    protected function slugMax(): int
    {
        return 255;
    }

    /**
     * Build an Arabic-friendly, lowercase slug. Falls back to preserving Arabic letters/numbers.
     */
    protected function makeSlug(string $source): string
    {
        // Try Laravel slug with Arabic locale
        $slug = Str::slug($source, '-', 'ar');

        // Fallback to a Unicode-friendly slug if empty (keeps Arabic letters and numbers)
        if ($slug === '') {
            $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}]+/u', '-', $source) ?? '';
            $slug = trim($slug, '-');
            $slug = mb_strtolower($slug, 'UTF-8');
        }

        // Enforce max length without suffix
        $max = $this->slugMax();
        if ($max > 0) {
            $slug = Str::limit($slug, $max, '');
        }

        return $slug;
    }

    /**
     * Normalize on assignment.
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $this->makeSlug((string) $value);
    }
}