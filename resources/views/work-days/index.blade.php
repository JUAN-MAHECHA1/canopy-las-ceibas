@extends('layouts.app')
@section('title', 'Turno del día')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Control de turno</h1>

    {{-- Liderazgo actual y transferencia --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <p class="text-sm text-slate-500 mb-1">Líder de turno actual</p>
        <p class="text-lg font-semibold text-emerald-800 mb-4">{{ $liderActual?->name ?? 'Sin asignar' }}</p>

        <form method="POST" action="{{ route('lider.work-days.leadership.transfer') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            @method('PUT')
            <select name="guia_id" required class="flex-1 border rounded-lg px-3 py-2">
                <option value="">Transferir liderazgo a...</option>
                @foreach ($guias as $guia)
                    @if (! $guia->isLider())
                        <option value="{{ $guia->id }}">{{ $guia->name }}</option>
                    @endif
                @endforeach
            </select>
            <button class="bg-slate-700 hover:bg-slate-800 text-white font-medium px-5 py-2 rounded-lg transition">
                Transferir
            </button>
        </form>
    </div>

    @if (! $workDay)
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <p class="mb-4 text-slate-600">Hoy aún no se ha abierto el turno.</p>
            <form method="POST" action="{{ route('lider.work-days.store') }}">
                @csrf
                <button class="bg-emerald-700 hover:bg-emerald-800 text-white font-medium px-5 py-2.5 rounded-lg transition">
                    Abrir turno de hoy
                </button>
            </form>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-6">
            <p class="mb-4 text-sm text-slate-500">
                Turno abierto: <span class="font-medium text-slate-800">{{ $workDay->fecha->translatedFormat('l d \d\e F') }}</span>
            </p>

            <form method="POST" action="{{ route('lider.work-days.guides.update') }}">
                @csrf
                @method('PUT')

                <p class="font-medium mb-3">Marca los guías activos hoy (reciben pago de hoy):</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    @foreach ($guias as $guia)
                        <label class="flex items-center gap-3 border rounded-lg px-4 py-3 cursor-pointer hover:bg-emerald-50 transition">
                            <input type="checkbox" name="guia_ids[]" value="{{ $guia->id }}"
                                   {{ in_array($guia->id, $guiaIdsActivos) ? 'checked' : '' }}
                                   class="h-4 w-4 text-emerald-700 rounded">
                            <span>{{ $guia->name }} @if($guia->isLider()) <span class="text-xs text-emerald-700 font-semibold">(Líder)</span> @endif</span>
                        </label>
                    @endforeach
                </div>

                <button class="bg-emerald-700 hover:bg-emerald-800 text-white font-medium px-5 py-2.5 rounded-lg transition">
                    Guardar guías activos
                </button>
            </form>
        </div>
    @endif
@endsection
