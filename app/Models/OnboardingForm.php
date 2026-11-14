<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingForm extends Model
{
    protected $fillable = [
        'onboarding_id',
        'primary_clients',
        'start_date',
        'household_address',
        'number_of_adults',
        'number_of_kids',
        'kids_ages',
        'household_goals',
        'dietary_preferences',
        'nutrition_preferences',
        'foods_to_avoid',
        'favorite_cuisines',
        'favorite_restaurants',
        'children_preferences',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'household_goals' => 'array',
        'dietary_preferences' => 'array',
        'nutrition_preferences' => 'array',
        'foods_to_avoid' => 'array',
        'favorite_cuisines' => 'array',
        'favorite_restaurants' => 'array',
        'children_preferences' => 'array',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function onboarding(): BelongsTo
    {
        return $this->belongsTo(Onboarding::class);
    }
}
