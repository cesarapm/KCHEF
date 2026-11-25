<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceMenuSelectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_menu_selection_id',
        'service_menu_option_id',
    ];

    public function selection(): BelongsTo
    {
        return $this->belongsTo(ServiceMenuSelection::class, 'service_menu_selection_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(ServiceMenuOption::class, 'service_menu_option_id');
    }
}

