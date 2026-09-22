@extends('layouts.app')
@section('title', 'Novedades')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Reportar novedad</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-xl shadow p-6 h-fit">
            <form method="POST" action="{{ route('guia.novedades.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Tipo de novedad</label>
                    <select name="tipo" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="cliente">Cliente</option>
                        <option value="equipo">Equipo</option>
                        <option value="incidente">Incidente</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Título (opcional)</label>
                    <input type="text" name="titulo" placeholder="Ej: Arnés dañado"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Descripción</label>
                    <textarea name="descripcion" rows="5" required
                              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                </div>

                <button class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-medium py-2.5 rounded-lg transition">
                    Reportar
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
            <p class="font-medium mb-4">Mis últimas novedades</p>
            <div class="space-y-3">
                @forelse ($novedades as $novedad)
                    <div class="border rounded-lg px-4 py-3">
                        <div class="flex items-center justify-between mb-1">
                            <span @class([
                                'text-xs font-semibold px-2 py-1 rounded-full',
                                'bg-sky-100 text-sky-700' => $novedad->tipo === 'cliente',
                                'bg-amber-100 text-amber-700' => $novedad->tipo === 'equipo',
                                'bg-red-100 text-red-700' => $novedad->tipo === 'incidente',
                            ])>{{ $novedad->etiquetaTipo() }}</span>
                            <span class="text-xs text-slate-400">{{ $novedad->created_at->translatedFormat('d M, h:i A') }}</span>
                        </div>
                        @if ($novedad->titulo)
                            <p class="font-medium text-sm">{{ $novedad->titulo }}</p>
                        @endif
                        <p class="text-sm text-slate-600">{{ $novedad->descripcion }}</p>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">Aún no has reportado novedades.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
