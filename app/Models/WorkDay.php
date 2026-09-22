<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkDay extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'activo', 'abierto_por', 'cerrado_at'];

    protected $casts = [
        'fecha' => 'date',
        'activo' => 'boolean',
        'cerrado_at' => 'datetime',
    ];

    public function lider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'abierto_por');
    }

    public function guiasActivos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'work_day_guides')->withTimestamps();
    }

    public function registros(): HasMany
    {
        return $this->hasMany(RegistroCliente::class);
    }

    public function totalPersonas(): int
    {
        return (int) $this->registros()->sum('cantidad_personas');
    }
}
