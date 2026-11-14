<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'title',
        'cost',
        'description',
        'includes',
        'active',
        'order',
    ];

    protected $casts = [
        'includes' => 'array',
        'active' => 'boolean',
        'cost' => 'decimal:2',
    ];

    public function onboardings()
    {
        return $this->hasMany(Onboarding::class);
    }
}

