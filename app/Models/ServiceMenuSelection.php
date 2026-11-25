<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class ServiceMenuSelection extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_CONFIRMED = 'confirmed';

    protected $fillable = [
        'service_menu_id',
        'user_id',
        'status',
        'selected_count',
        'required_min_count',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $selection): void {
            $menu = $selection->menu()->first();
            if (!$menu) {
                return;
            }

            $activeOptionsCount = $menu->options()
                ->where('is_active', true)
                ->count();

            $minPercent = max(1, min(100, (int) $menu->min_selection_percent));
            $requiredMinCount = (int) ceil($activeOptionsCount * ($minPercent / 100));

            $selectedCount = $selection->exists
                ? $selection->items()->count()
                : 0;

            $selection->required_min_count = $requiredMinCount;
            $selection->selected_count = $selectedCount;

            if (
                $selection->status === self::STATUS_CONFIRMED
                && $selectedCount < $requiredMinCount
            ) {
                throw ValidationException::withMessages([
                    'status' => "The client must select at least {$requiredMinCount} options ({$minPercent}%).",
                ]);
            }

            if ($selection->status === self::STATUS_CONFIRMED && !$selection->confirmed_at) {
                $selection->confirmed_at = now();
            }
        });
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(ServiceMenu::class, 'service_menu_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceMenuSelectionItem::class);
    }
}

