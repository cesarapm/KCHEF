<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\ValidationException;

class OrderService extends Model
{
    use HasFactory;

    public const STATUS_PENDIENTE_CONFIGURACION = 'pendiente_configuracion';
    public const STATUS_MENU_ENVIADO = 'menu_enviado';
    public const STATUS_PENDIENTE_SELECCION = 'pendiente_seleccion';
    public const STATUS_SELECCION_CONFIRMADA = 'seleccion_confirmada';
    public const STATUS_AGENDADO = 'agendado';
    public const STATUS_EN_PREPARACION = 'en_preparacion';
    public const STATUS_EN_PROCESO = 'en_proceso';
    public const STATUS_EN_ENTREGA = 'en_entrega';
    public const STATUS_HOME_SERVICE = 'home_service';
    public const STATUS_COMPLETADO = 'completado';

    protected $fillable = [
        'order_id',
        'service_number',
        'assigned_chef_id',
        'title',
        'description',
        'scheduled_at',
        'status',
        'completed_at',
        'configuration',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'configuration' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $service): void {
            $order = $service->order()->first();

            if (!$order) {
                throw ValidationException::withMessages([
                    'order_id' => 'La orden asociada no existe.',
                ]);
            }

            $maxServices = max(1, (int) $order->service_count);

            if ($service->service_number < 1 || $service->service_number > $maxServices) {
                throw ValidationException::withMessages([
                    'service_number' => "El número de servicio debe estar entre 1 y {$maxServices} para esta orden.",
                ]);
            }

            $existingServicesQuery = self::query()->where('order_id', $service->order_id);
            if ($service->exists) {
                $existingServicesQuery->whereKeyNot($service->id);
            }

            $existingServices = $existingServicesQuery->count();
            if (!$service->exists && $existingServices >= $maxServices) {
                throw ValidationException::withMessages([
                    'order_id' => "Esta orden ya alcanzó su límite de {$maxServices} servicios.",
                ]);
            }
        });

        static::updating(function (self $service): void {
            if ($service->isDirty('status')) {
                $service->completed_at = $service->status === self::STATUS_COMPLETADO ? now() : null;
            }
        });

        static::updated(function (self $service): void {
            if ($service->wasChanged('status')) {
                $service->statusHistories()->create([
                    'from_status' => $service->getOriginal('status'),
                    'to_status' => $service->status,
                    'changed_by' => auth()->id(),
                    'notes' => 'Actualización de estado desde gestión de servicio.',
                ]);
            }

            if ($service->wasChanged('scheduled_at') && $service->scheduled_at) {
                $service->scheduleChanges()->create([
                    'old_date' => $service->getOriginal('scheduled_at'),
                    'new_date' => $service->scheduled_at,
                    'changed_by' => auth()->id(),
                    'reason' => 'Actualización de agenda.',
                ]);
            }
        });
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDIENTE_CONFIGURACION => 'Pendiente de configuración',
            self::STATUS_MENU_ENVIADO => 'Menú enviado',
            self::STATUS_PENDIENTE_SELECCION => 'Pendiente de selección',
            self::STATUS_SELECCION_CONFIRMADA => 'Selección confirmada',
            self::STATUS_AGENDADO => 'Servicio agendado',
            self::STATUS_EN_PREPARACION => 'En preparación',
            self::STATUS_EN_PROCESO => 'En proceso',
            self::STATUS_EN_ENTREGA => 'En entrega',
            self::STATUS_HOME_SERVICE => 'Home service',
            self::STATUS_COMPLETADO => 'Completado',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedChef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_chef_id')->withDefault();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ServiceStatusHistory::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(ServiceMenu::class);
    }

    public function groceryList(): HasOne
    {
        return $this->hasOne(ServiceGroceryList::class);
    }

    public function chefNotes(): HasMany
    {
        return $this->hasMany(ServiceChefNote::class);
    }

    public function scheduleChanges(): HasMany
    {
        return $this->hasMany(ServiceScheduleChange::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(ServiceNotification::class);
    }
}

