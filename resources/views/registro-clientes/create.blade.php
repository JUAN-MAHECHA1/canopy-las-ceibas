@extends('layouts.app')
@section('title', 'Registrar Cliente')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Registrar cliente</h1>

    @if (! $workDay || ! $workDay->activo)
        <div class="bg-amber-100 border border-amber-300 text-amber-800 rounded-lg px-4 py-3 mb-6 text-sm">
            El líder de turno aún no ha abierto el día. No se pueden registrar clientes.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Formulario --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6"
             x-data="{ empresaId: '', empresas: {{ $empresas->keyBy('id')->map->tipo_registro->toJson() }} }">
            <form method="POST" action="{{ route('guia.registro-clientes.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Empresa</label>
                    <select name="empresa_id" x-model="empresaId" required
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Selecciona una empresa</option>
                        @foreach ($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="empresas[empresaId] === 'codigo'">
                    <label class="block text-sm font-medium mb-1">Código de reserva</label>
                    <input type="text" name="codigo_reserva" placeholder="Ej: R37-27"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div x-show="empresas[empresaId] === 'nombre' || empresaId === ''">
                    <label class="block text-sm font-medium mb-1">Nombre y apellido del cliente</label>
                    <input type="text" name="nombre_cliente" placeholder="Nombre completo"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Cédula (opcional)</label>
                        <input type="text" name="cedula"
                               class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Cantidad de personas</label>
                        <input type="number" name="cantidad_personas" min="1" value="1" required
                               class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>

                <button type="submit" {{ (! $workDay || ! $workDay->activo) ? 'disabled' : '' }}
                        class="w-full bg-emerald-700 hover:bg-emerald-800 disabled:bg-slate-300 text-white font-medium py-2.5 rounded-lg transition">
                    Registrar
                </button>
            </form>
        </div>

        {{-- Resumen del día --}}
        <div class="bg-white rounded-xl shadow p-6 h-fit">
            <p class="text-sm text-slate-500">Ganancia semana actual</p>
            <p class="text-2xl font-bold text-emerald-800 mb-4">${{ number_format($gananciaSemanal, 0, ',', '.') }}</p>

            <p class="text-sm font-medium text-slate-600 mb-2">Mis registros de hoy ({{ $registrosHoy->count() }})</p>
            <ul class="space-y-2 max-h-72 overflow-y-auto text-sm">
                @forelse ($registrosHoy as $registro)
                    <li class="flex justify-between border-b pb-1">
                        <span>{{ $registro->identificador }}</span>
                        <span class="text-slate-500">{{ $registro->cantidad_personas }} pax</span>
                    </li>
                @empty
                    <li class="text-slate-400">Aún no has registrado clientes hoy.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
