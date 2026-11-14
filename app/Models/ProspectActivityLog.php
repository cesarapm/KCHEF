<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectActivityLogFactory> */
    use HasFactory;

    protected $fillable = [
        'prospect_id',
        'user_id',
        'action',
        'description',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_value' => 'json',
        'new_value' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
