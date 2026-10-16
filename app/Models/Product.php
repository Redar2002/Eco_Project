<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'sale_price',
        'stock', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Slug unique généré à partir du nom
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $base = Str::slug($product->name);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->whereKeyNot($product->getKey())->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $product->slug = $slug;
            }
        });
    }

    /* ---------- Relations ---------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position')->orderBy('id');
    }

    /** Première image (pour les listes, évite de charger toute la galerie). */
    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany(['position' => 'min', 'id' => 'min']);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ---------- Scopes ---------- */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtres du catalogue : catégorie (slug), prix min/max (sur le prix effectif),
     * recherche par nom et tri.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // Prix effectif = prix promo s'il existe, sinon prix normal
        $effective = 'COALESCE(sale_price, price)';

        return $query
            ->when($filters['q'] ?? null, fn (Builder $q, $search) => $q->where('name', 'like', '%'.$search.'%'))
            ->when($filters['categorie'] ?? null, fn (Builder $q, $slug) => $q->whereHas('category', fn (Builder $c) => $c->where('slug', $slug)))
            ->when(is_numeric($filters['min'] ?? null), fn (Builder $q) => $q->whereRaw("$effective >= ?", [(float) $filters['min']]))
            ->when(is_numeric($filters['max'] ?? null), fn (Builder $q) => $q->whereRaw("$effective <= ?", [(float) $filters['max']]))
            ->when($filters['promo'] ?? null, fn (Builder $q) => $q->whereNotNull('sale_price'))
            ->tap(fn (Builder $q) => match ($filters['tri'] ?? 'nouveautes') {
                'prix-asc' => $q->orderByRaw("$effective asc"),
                'prix-desc' => $q->orderByRaw("$effective desc"),
                'nom' => $q->orderBy('name'),
                default => $q->latest()->orderByDesc('id'),
            });
    }

    /* ---------- Attributs calculés ---------- */

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->price;
    }

    /** Prix réellement payé par le client. */
    public function getCurrentPriceAttribute(): float
    {
        return $this->is_on_sale ? (float) $this->sale_price : (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->is_on_sale) {
            return 0;
        }

        return (int) round(100 - ((float) $this->sale_price / (float) $this->price * 100));
    }

    public function getIsNewAttribute(): bool
    {
        return $this->created_at !== null
            && $this->created_at->gt(now()->subDays(config('shop.new_product_days')));
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->relationLoaded('mainImage')
            ? $this->mainImage
            : ($this->relationLoaded('images') ? $this->images->first() : $this->mainImage);

        return $image?->url ?? asset('images/placeholder.svg');
    }
}
