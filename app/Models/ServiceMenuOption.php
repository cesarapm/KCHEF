<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceMenuOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_menu_id',
        'master_menu_dish_id',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(ServiceMenu::class, 'service_menu_id');
    }

    public function masterDish(): BelongsTo
    {
        return $this->belongsTo(MasterMenuDish::class, 'master_menu_dish_id');
    }
}

