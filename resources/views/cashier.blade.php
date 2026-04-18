<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @filamentStyles
    @livewireStyles
</head>
<body class="min-h-screen bg-stone-100 text-gray-900 antialiased">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Cashier Session</p>
                    <p class="mt-1 text-sm text-slate-700">{{ auth()->user()?->nama }} ({{ auth()->user()?->username }})</p>
                </div>

                <form method="POST" action="{{ route('cashier.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        @livewire('cashier-terminal')
    </div>

    @livewireScripts
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
