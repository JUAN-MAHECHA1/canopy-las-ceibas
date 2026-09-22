@extends('layouts.app')
@section('title', 'Novedades')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Novedades reportadas</h1>

    <div class="flex gap-2 mb-4 text-sm">
        <a href="{{ route('admin.novedades.index') }}"
           class="px-3 py-1.5 rounded-full {{ ! $tipoActual ? 'bg-emerald-700 text-white' : 'bg-white border' }}">Todas</a>
        <a href="{{ route('admin.novedades.index', ['tipo' => 'cliente']) }}"
           class="px-3 py-1.5 rounded-full {{ $tipoActual === 'cliente' ? 'bg-sky-700 text-white' : 'bg-white border' }}">Clientes</a>
        <a href="{{ route('admin.novedades.index', ['tipo' => 'equipo']) }}"
           class="px-3 py-1.5 rounded-full {{ $tipoActual === 'equipo' ? 'bg-amber-700 text-white' : 'bg-white border' }}">Equipos</a>
        <a href="{{ route('admin.novedades.index', ['tipo' => 'incidente']) }}"
           class="px-3 py-1.5 rounded-full {{ $tipoActual === 'incidente' ? 'bg-red-700 text-white' : 'bg-white border' }}">Incidentes</a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Fecha</th>
                    <th class="px-5 py-3 text-left">Guía</th>
                    <th class="px-5 py-3 text-left">Tipo</th>
                    <th class="px-5 py-3 text-left">Título</th>
                    <th class="px-5 py-3 text-left">Descripción</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($novedades as $novedad)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 whitespace-nowrap">{{ $novedad->created_at->translatedFormat('d M Y, h:i A') }}</td>
                        <td class="px-5 py-3">{{ $novedad->guia->name }}</td>
                        <td class="px-5 py-3">{{ $novedad->etiquetaTipo() }}</td>
                        <td class="px-5 py-3">{{ $novedad->titulo ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $novedad->descripcion }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">Sin novedades registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $novedades->links() }}</div>
@endsection
