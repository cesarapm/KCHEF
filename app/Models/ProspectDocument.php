<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectDocument extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'prospect_id',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }
}
