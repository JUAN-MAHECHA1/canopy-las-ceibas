<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Novedad extends Model
{
    use HasFactory;

    protected $fillable = ['guia_id', 'work_day_id', 'tipo', 'titulo', 'descripcion'];

    public function guia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guia_id');
    }

    public function workDay(): BelongsTo
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function scopeTipo(Builder $query, ?string $tipo): Builder
    {
        return $tipo ? $query->where('tipo', $tipo) : $query;
    }

    public function etiquetaTipo(): string
    {
        return match ($this->tipo) {
            'cliente' => 'Cliente',
            'equipo' => 'Equipo',
            'incidente' => 'Incidente',
            default => $this->tipo,
        };
    }
}
