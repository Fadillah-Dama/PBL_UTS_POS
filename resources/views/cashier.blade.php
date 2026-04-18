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
        @livewire('cashier-terminal')
    </div>

    @livewireScripts
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
