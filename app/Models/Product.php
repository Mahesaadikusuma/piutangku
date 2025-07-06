<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory, Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($product) {
            $product->slug = SlugService::createSlug($product, 'slug', $product->name);
        });
    }


    // public function price(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value) => 'Rp ' . number_format($value, 0, ','),
    //         set: fn($value) => (int) str_replace(',', '', $value)
    //     );
    // }

    public function stock(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 0, ','),
            set: fn($value) => (int) str_replace(',', '', $value)
        );
    }



    /**
     * Get the categories that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
