<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'channel',
        'recipient',
        'payload',
        'status',
        'sent_at',
        'error',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }
}

