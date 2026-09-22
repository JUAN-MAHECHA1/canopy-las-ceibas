<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'work_day_id', 'guia_registrador_id',
        'nombre_cliente', 'codigo_reserva', 'cedula',
        'cantidad_personas', 'hora_registro',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function workDay(): BelongsTo
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function guiaRegistrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guia_registrador_id');
    }

    /** Muestra el código de reserva o el nombre, según cómo registre la empresa. */
    public function getIdentificadorAttribute(): string
    {
        return $this->empresa->usaCodigo()
            ? ($this->codigo_reserva ?? '—')
            : ($this->nombre_cliente ?? '—');
    }
}
