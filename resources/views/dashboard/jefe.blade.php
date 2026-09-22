@extends('layouts.app')
@section('title', 'Panel Jefes')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-emerald-900">Reporte Canopy Las Ceibas</h1>
        <form method="GET" class="flex items-center gap-2 text-sm">
            <label>Semana de:</label>
            <input type="date" name="semana" value="{{ $inicioSemana->toDateString() }}"
                   class="border rounded-md px-2 py-1" onchange="this.form.submit()">
        </form>
    </div>

    {{-- Tabla matriz Empresa x Día, igual a la planilla en Excel --}}
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Empresas</th>
                        @foreach ($matriz['dias'] as $dia)
                            <th class="px-4 py-3">{{ $dia }}</th>
                        @endforeach
                        <th class="px-4 py-3 font-bold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($matriz['filas'] as $fila)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-left font-medium" style="border-left: 4px solid {{ $fila['color'] ?? '#cbd5e1' }}">
                                {{ $fila['empresa'] }}
                            </td>
                            @foreach ($matriz['dias'] as $dia)
                                <td class="px-4 py-3">{{ $fila['dias'][$dia] ?? 0 ?: '' }}</td>
                            @endforeach
                            <td class="px-4 py-3 font-bold bg-slate-50">{{ $fila['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-emerald-100 font-bold">
                    <tr>
                        <td class="px-4 py-3 text-left">Total</td>
                        @foreach ($matriz['dias'] as $dia)
                            <td class="px-4 py-3">{{ $matriz['totales_dia'][$dia] }}</td>
                        @endforeach
                        <td class="px-4 py-3">{{ $matriz['total_general'] }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Comparación en dinero --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($reportePorEmpresa as $r)
            <div class="bg-white rounded-xl shadow p-5 border-l-4" style="border-color: {{ $r['color'] ?? '#cbd5e1' }}">
                <p class="text-sm text-slate-500">{{ $r['empresa'] }}</p>
                <p class="text-2xl font-bold text-slate-800">{{ $r['total_personas'] }} personas</p>
                <p class="text-emerald-700 font-medium">${{ number_format($r['total_dinero'], 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>
@endsection
