<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SurveyEase' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800">
    <header class="sticky top-0 z-40" style="background-color: #004179;">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-xl font-bold text-white tracking-tight">
                    Survey<span style="color: #f3c404;">Ease</span>
                </a>
                <nav class="flex items-center gap-1 sm:gap-3">
                    <a href="{{ route('surveys.browse') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition">
                        Browse Surveys
                    </a>

                    @auth
                        <a href="{{ route('surveys.my') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition">
                            My Surveys
                        </a>
                        <a href="{{ route('surveys.create') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition">
                            Create Survey
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition cursor-pointer">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-semibold transition" style="background-color: #f3c404; color: #004179;">
                            Register
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>
    <x-toaster />
    <main class="flex-1">
        {{ $slot }}
    </main>
    <footer style="background-color: #004179;" class="text-white/70">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm">
                    &copy; {{ date('Y') }} <span class="text-white font-semibold">Survey<span style="color: #f3c404;">Ease</span></span>. All rights reserved.
                </div>
                <div class="flex gap-6 text-sm">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <a href="{{ route('surveys.browse') }}" class="hover:text-white transition">Browse</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>