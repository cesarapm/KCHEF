<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceScheduleChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'old_date',
        'new_date',
        'changed_by',
        'reason',
        'changed_at',
    ];

    protected $casts = [
        'old_date' => 'datetime',
        'new_date' => 'datetime',
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

