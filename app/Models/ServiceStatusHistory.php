<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by')->withDefault();
    }
}

