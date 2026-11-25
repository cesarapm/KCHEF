<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceChefNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'chef_id',
        'note',
        'priority',
        'is_private',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    public static function priorityOptions(): array
    {
        return [
            self::PRIORITY_LOW => 'Low Priority',
            self::PRIORITY_MEDIUM => 'Medium Priority',
            self::PRIORITY_HIGH => 'High Priority',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_service_id');
    }

    public function chef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chef_id')->withDefault();
    }
}
