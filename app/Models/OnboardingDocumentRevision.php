<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingDocumentRevision extends Model
{
    /** @use HasFactory<\Database\Factories\OnboardingDocumentRevisionFactory> */
    use HasFactory;

    protected $fillable = [
        'document_id',
        'admin_user_id',
        'status',
        'comment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function document(): BelongsTo
    {
        return $this->belongsTo(OnboardingDocument::class);
    }

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id')->withDefault();
    }
}
