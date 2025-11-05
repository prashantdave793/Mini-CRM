<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Mini-CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 text-center">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Welcome to {{ config('app.name', 'Mini-CRM') }}
        </h1>

        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
            Manage your customers, leads, and communications efficiently.
        </p>

        <div class="flex gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>
</html>
