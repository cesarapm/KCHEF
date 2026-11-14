<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectRegistrationToken extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectRegistrationTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'prospect_id',
        'token',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }
}
