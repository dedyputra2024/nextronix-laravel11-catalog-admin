<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'sale_price',
        'stock',
        'weight_gram',
        'length_cm',
        'width_cm',
        'height_cm',
        'image',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'weight_gram' => 'integer',
            'length_cm' => 'integer',
            'width_cm' => 'integer',
            'height_cm' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?: $this->price);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->sale_price || (float) $this->price <= 0) {
            return null;
        }

        return (int) round((1 - ((float) $this->sale_price / (float) $this->price)) * 100);
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->image ?: 'img/product-1.png';

        if (Str::startsWith($image, ['http://', 'https://', '/'])) {
            return $image;
        }

        if (Str::startsWith($image, ['products/', 'public/'])) {
            return Storage::url(Str::after($image, 'public/'));
        }

        return asset($image);
    }
}
