<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Canopy Las Ceibas')</title>

    {{-- PWA: hace que el navegador ofrezca "Instalar" / "Agregar a pantalla de inicio" --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#065f46">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

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

    {{-- PWA: registra el Service Worker que cachea el "cascarón" visual --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {
                    // Si falla (ej. en local sin https), no rompe nada, la app sigue funcionando normal.
                });
            });
        }
    </script>
</body>
</html>
