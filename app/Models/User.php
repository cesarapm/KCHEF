<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // Agregado para los roles (admin/cliente)
        'activo'
    ];

    /**
     * Los atributos que se deben ocultar para los arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function canAccessPanel(): bool
    {
        return $this->role === 'admin';
    }

    public function profile()
    {
        return $this->hasOne(\App\Models\UserProfile::class);
    }

    public function prospects()
    {
        return $this->hasMany(\App\Models\Prospect::class);
    }

    public function onboardings()
    {
        return $this->hasMany(\App\Models\Onboarding::class);
    }

    public function onboardingDocuments()
    {
        return $this->hasMany(\App\Models\OnboardingDocument::class);
    }

    public function documentRevisions()
    {
        return $this->hasMany(\App\Models\OnboardingDocumentRevision::class, 'admin_user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ProspectActivityLog::class);
    }
}
