<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'pending' => 'Pendente',
        'confirmed' => 'Confirmado',
        'preparing' => 'Em preparação',
        'out_for_delivery' => 'Saiu para entrega',
        'delivered' => 'Entregue',
        'cancelled' => 'Cancelado',
    ];

    public const PAYMENT_LABELS = [
        'card' => 'Cartão',
        'pix' => 'PIX',
    ];

    public const CANCELLABLE_STATUSES = ['pending', 'confirmed'];

    protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_code',
        'shipping_street',
        'shipping_number',
        'shipping_city',
        'payment_method',
        'status',
        'subtotal',
        'discount',
        'shipping_cost',
        'total',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function paymentLabel(): string
    {
        return self::PAYMENT_LABELS[$this->payment_method] ?? $this->payment_method;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, self::CANCELLABLE_STATUSES, true);
    }
}
