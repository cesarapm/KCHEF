<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Prospect extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'city',
        'status',
        'rejection_reason',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function registrationTokens(): HasMany
    {
        return $this->hasMany(ProspectRegistrationToken::class);
    }

    public function onboarding(): HasOne
    {
        return $this->hasOne(Onboarding::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ProspectActivityLog::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProspectDocument::class);
    }
}
