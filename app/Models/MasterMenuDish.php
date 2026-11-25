<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterMenuDish extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description_markdown',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceMenuOptions(): HasMany
    {
        return $this->hasMany(ServiceMenuOption::class, 'master_menu_dish_id');
    }

    public function orderDishes(): HasMany
    {
        return $this->hasMany(OrderDish::class, 'master_menu_dish_id');
    }
}

