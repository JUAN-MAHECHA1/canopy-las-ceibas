<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo_registro', 'color', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function registros(): HasMany
    {
        return $this->hasMany(RegistroCliente::class);
    }

    public function jefes(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function usaCodigo(): bool
    {
        return $this->tipo_registro === 'codigo';
    }

    public function usaNombre(): bool
    {
        return $this->tipo_registro === 'nombre';
    }
}
