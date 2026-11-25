<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceGroceryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_grocery_list_id',
        'item',
        'quantity',
        'description',
        'is_checked',
        'checked_at',
        'sort_order',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function groceryList(): BelongsTo
    {
        return $this->belongsTo(ServiceGroceryList::class, 'service_grocery_list_id');
    }
}

