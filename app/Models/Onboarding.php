<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Onboarding extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingFactory> */
    use HasFactory;

    protected $fillable = [
        'prospect_id',
        'user_id',
        'plan_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class)->withDefault();
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(OnboardingRequirement::class);
    }

    public function form(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OnboardingForm::class);
    }

    public function forms(): HasMany
    {
        return $this->hasMany(OnboardingForm::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OnboardingDocument::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function latestOrder(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }
}
