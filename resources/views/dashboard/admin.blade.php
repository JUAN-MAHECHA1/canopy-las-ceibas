@extends('layouts.app')
@section('title', 'Panel Administrador')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-emerald-900">Panel de Administrador</h1>
        <div class="flex gap-3 text-sm">
            <a href="{{ route('admin.guides.index') }}" class="text-emerald-700 hover:underline font-medium">Gestionar guías / líder</a>
            <a href="{{ route('admin.novedades.index') }}" class="text-emerald-700 hover:underline font-medium">Ver novedades</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-emerald-600">
            <p class="text-sm text-slate-500">Total esta semana</p>
            <p class="text-3xl font-bold text-emerald-800">
                {{ number_format($pagosSemanales->first()['total_personas'] ?? 0) }} personas
            </p>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-emerald-600">
            <p class="text-sm text-slate-500">Dinero generado esta semana</p>
            <p class="text-3xl font-bold text-emerald-800">
                ${{ number_format($pagosSemanales->first()['total_dinero'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <h2 class="font-semibold px-5 py-4 border-b bg-slate-50">Pagos semanales</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Semana</th>
                        <th class="px-5 py-3 text-right">Total personas</th>
                        <th class="px-5 py-3 text-right">Total dinero</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($pagosSemanales as $fila)
                        <tr class="hover:bg-emerald-50 transition">
                            <td class="px-5 py-3">{{ $fila['semana'] }}</td>
                            <td class="px-5 py-3 text-right">{{ number_format($fila['total_personas']) }}</td>
                            <td class="px-5 py-3 text-right font-medium">${{ number_format($fila['total_dinero'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
