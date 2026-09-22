@extends('layouts.app')
@section('title', 'Mi Panel')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Hola, {{ auth()->user()->name }}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-emerald-600">
            <p class="text-sm text-slate-500">Ganancia semana actual</p>
            <p class="text-3xl font-bold text-emerald-800">${{ number_format($gananciaSemanal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-sky-600">
            <p class="text-sm text-slate-500">Días trabajados esta semana</p>
            <p class="text-3xl font-bold text-sky-800">{{ $diasTrabajados }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('guia.registro-clientes.create') }}"
           class="inline-block bg-emerald-700 hover:bg-emerald-800 text-white font-medium px-5 py-3 rounded-lg shadow transition">
            + Registrar cliente
        </a>
        <a href="{{ route('guia.novedades.create') }}"
           class="inline-block bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-5 py-3 rounded-lg shadow transition">
            Reportar novedad
        </a>
        @auth
            @if (auth()->user()->isLider())
                <a href="{{ route('lider.work-days.index') }}"
                   class="inline-block bg-slate-800 hover:bg-slate-900 text-white font-medium px-5 py-3 rounded-lg shadow transition">
                    Panel de turno (líder)
                </a>
            @endif
        @endauth
    </div>
@endsection
