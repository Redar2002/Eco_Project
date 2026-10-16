<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image', 'is_featured', 'position'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'position' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Génère automatiquement le slug à partir du nom s'il est absent
        static::saving(function (Category $category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Le menu de navigation est mis en cache : on l'invalide à chaque modification
        static::saved(fn () => Cache::forget('nav.categories'));
        static::deleted(fn () => Cache::forget('nav.categories'));
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('name');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}
