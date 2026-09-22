<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Canopy Las Ceibas')</title>
    {{-- En producción usar Vite/Tailwind compilado (ver docs/INSTALACION.md). CDN solo para prototipo rápido. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <nav class="bg-emerald-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <span class="font-bold text-lg tracking-wide">🌿 Canopy Las Ceibas</span>
            <div class="flex items-center gap-4 text-sm">
                <span class="hidden sm:inline">{{ auth()->user()->name ?? '' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-emerald-900 hover:bg-emerald-950 px-3 py-1.5 rounded-md transition">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
