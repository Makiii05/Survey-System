<x-layout title="Login - SurveyEase">
    <div class="min-h-[calc(100vh-12rem)] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold" style="color: #004179;">
                        Survey<span style="color: #f3c404;">Ease</span>
                    </h1>
                    <p class="text-gray-500 mt-2">Sign in to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition"
                            style="focus:ring-color: #004179;"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-lg text-white font-semibold transition hover:opacity-90 cursor-pointer"
                        style="background-color: #004179;">
                        Login
                    </button>
                </form>
                <div class="mt-6 text-center text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium hover:underline" style="color: #004179;">Register</a>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">&larr; Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>