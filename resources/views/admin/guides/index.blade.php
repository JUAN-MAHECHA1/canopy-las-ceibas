@extends('layouts.app')
@section('title', 'Guías')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-emerald-900">Guías y liderazgo de turno</h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Correo</th>
                    <th class="px-5 py-3 text-center">Líder actual</th>
                    <th class="px-5 py-3 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($guias as $guia)
                    <tr class="hover:bg-emerald-50 transition">
                        <td class="px-5 py-3">{{ $guia->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $guia->email }}</td>
                        <td class="px-5 py-3 text-center">
                            @if ($guia->isLider())
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-1 rounded-full">Líder</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @unless ($guia->isLider())
                                <form method="POST" action="{{ route('admin.guides.set-lider') }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="guia_id" value="{{ $guia->id }}">
                                    <button class="text-emerald-700 hover:underline text-sm font-medium">
                                        Hacer líder
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
