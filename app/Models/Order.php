<?php

namespace App\Models;

use App\Actions\GenerateOrderServicesAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_CONFIRMED = 'confirmed';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'order_number',
        'user_id',
        'plan_id',
        'onboarding_id',
        'payment_status',
        'payment_reference',
        'paid_at',
        'service_count',
        'metadata',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });

        static::saved(function (self $order): void {
            if (
                $order->payment_status === self::PAYMENT_CONFIRMED
                && ($order->wasChanged('payment_status') || $order->wasRecentlyCreated)
            ) {
                app(GenerateOrderServicesAction::class)->handle($order);
            }
        });
    }

    public static function paymentStatusOptions(): array
    {
        return [
            self::PAYMENT_PENDING => 'Pendiente',
            self::PAYMENT_CONFIRMED => 'Confirmado',
            self::PAYMENT_FAILED => 'Fallido',
            self::PAYMENT_REFUNDED => 'Reembolsado',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class)->withDefault();
    }

    public function onboarding(): BelongsTo
    {
        return $this->belongsTo(Onboarding::class)->withDefault();
    }

    public function services(): HasMany
    {
        return $this->hasMany(OrderService::class);
    }

    public function orderDishes(): HasMany
    {
        return $this->hasMany(OrderDish::class);
    }

    protected static function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd-His') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }
}

