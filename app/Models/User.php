<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'empresa_id',
        'es_lider', 'cedula', 'telefono', 'activo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'es_lider' => 'boolean',
        'activo' => 'boolean',
    ];

    // ---------- Relaciones ----------

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function registrosRealizados(): HasMany
    {
        return $this->hasMany(RegistroCliente::class, 'guia_registrador_id');
    }

    public function workDaysAbiertos(): HasMany
    {
        return $this->hasMany(WorkDay::class, 'abierto_por');
    }

    /** Días en los que este guía estuvo activo (y por tanto recibe pago). */
    public function workDaysComoGuia(): BelongsToMany
    {
        return $this->belongsToMany(WorkDay::class, 'work_day_guides')->withTimestamps();
    }

    // ---------- Helpers de rol ----------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isJefe(): bool
    {
        return $this->role === 'jefe';
    }

    public function isGuia(): bool
    {
        return $this->role === 'guia';
    }

    /** El liderazgo es un atributo del guía, no un rol independiente. */
    public function isLider(): bool
    {
        return $this->role === 'guia' && $this->es_lider === true;
    }

    // ---------- Scopes ----------

    public function scopeGuias(Builder $query): Builder
    {
        return $query->where('role', 'guia')->where('activo', true);
    }

    public function scopeLideres(Builder $query): Builder
    {
        return $query->where('role', 'guia')->where('es_lider', true);
    }
}
