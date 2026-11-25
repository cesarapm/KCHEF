<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'title',
        'description',
        'menu_month',
        'additional_info',
        'min_selection_percent',
        'is_active',
        'is_sent',
        'sent_at',
    ];

    protected $casts = [
        'menu_month' => 'date',
        'is_active' => 'boolean',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(ServiceMenuOption::class);
    }

    public function selection(): HasOne
    {
        return $this->hasOne(ServiceMenuSelection::class);
    }
}

