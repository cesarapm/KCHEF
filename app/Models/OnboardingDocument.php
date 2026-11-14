<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnboardingDocument extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'onboarding_id',
        'requirement_id',
        'document_type',
        'user_id',
        'file_path',
        'file_name',
        'file_size',
        'description',
        'status',
        'uploaded_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function onboarding(): BelongsTo
    {
        return $this->belongsTo(Onboarding::class);
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(OnboardingRequirement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(OnboardingDocumentRevision::class, 'document_id');
    }
}
