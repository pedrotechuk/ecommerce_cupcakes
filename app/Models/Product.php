<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (config('filesystems.product_image_disk') === 'cloudinary') {
                return cloudinary()->getImage($this->image)->toUrl();
            }

            return Storage::disk('public')->url($this->image);
        }

        $slug = $this->category?->slug;

        if ($slug && file_exists(public_path("images/placeholders/{$slug}.svg"))) {
            return asset("images/placeholders/{$slug}.svg");
        }

        return asset('images/cupcake-placeholder.svg');
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function wasPurchasedBy(User $user): ?OrderItem
    {
        return OrderItem::query()
            ->where('product_id', $this->id)
            ->whereDoesntHave('review')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', '!=', 'cancelled');
            })
            ->first();
    }
}
