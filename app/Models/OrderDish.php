<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDish extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'master_menu_dish_id',
        'is_selected_by_client',
        'selected_at',
    ];

    protected $casts = [
        'is_selected_by_client' => 'boolean',
        'selected_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $orderDish): void {
            if ($orderDish->isDirty('is_selected_by_client')) {
                $orderDish->selected_at = $orderDish->is_selected_by_client ? now() : null;
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function masterDish(): BelongsTo
    {
        return $this->belongsTo(MasterMenuDish::class, 'master_menu_dish_id');
    }
}

