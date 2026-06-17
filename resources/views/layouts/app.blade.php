<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skolu Portāls')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-14">
                <div class="flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="font-bold text-lg text-indigo-600 mr-6">Skolu Portāls</a>
                    <a href="{{ route('subjects.index') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-indigo-600">Priekšmeti</a>
                    <a href="{{ route('grades.index') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-indigo-600">Atzīmes</a>
                    <a href="{{ route('calculator') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-indigo-600">Kalkulators</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
