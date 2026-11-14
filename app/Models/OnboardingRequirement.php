<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnboardingRequirement extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingRequirementFactory> */
    use HasFactory;

    protected $fillable = [
        'onboarding_id',
        'title',
        'description',
        'required',
        'allowed_formats',
        'max_file_size',
        'order',
    ];

    protected $casts = [
        'required' => 'boolean',
        'allowed_formats' => 'json',
        'max_file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function onboarding(): BelongsTo
    {
        return $this->belongsTo(Onboarding::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OnboardingDocument::class, 'requirement_id');
    }
}
