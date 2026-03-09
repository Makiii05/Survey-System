<x-layout title="SurveyEase - Create & Share Surveys">
    <section class="relative overflow-hidden" style="background-color: #004179;">
        <div class="px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                    Survey<span style="color: #f3c404;">Ease</span>
                </h1>
                <p class="mt-4 text-lg sm:text-xl text-white/80">
                    Create, share, and analyze surveys with ease. The simplest way to gather feedback and insights.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('surveys.create') }}"
                            class="px-8 py-3 rounded-lg font-semibold text-lg transition hover:opacity-90"
                            style="background-color: #f3c404; color: #004179;">
                            Create a Survey
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="px-8 py-3 rounded-lg font-semibold text-lg transition hover:opacity-90"
                            style="background-color: #f3c404; color: #004179;">
                            Get Started
                        </a>
                    @endauth
                    <a href="{{ route('surveys.browse') }}"
                        class="px-8 py-3 rounded-lg font-semibold text-lg border-2 border-white text-white hover:bg-white/10 transition">
                        View Surveys
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>
    <section class="py-20 bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold" style="color: #004179;">Why SurveyEase?</h2>
                <p class="text-gray-500 mt-2">Everything you need to create professional surveys</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background-color: #f3c404;">
                        <svg class="w-6 h-6" style="color: #004179;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2" style="color: #004179;">Easy to Create</h3>
                    <p class="text-gray-500">Build professional surveys in minutes with our intuitive interface. No technical skills required.</p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background-color: #f3c404;">
                        <svg class="w-6 h-6" style="color: #004179;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2" style="color: #004179;">Multiple Question Types</h3>
                    <p class="text-gray-500">Text, radio, checkbox, dropdown, date, time, scale, and more. Choose the best format for your needs.</p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-4" style="background-color: #f3c404;">
                        <svg class="w-6 h-6" style="color: #004179;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2" style="color: #004179;">Easy to Share</h3>
                    <p class="text-gray-500">Share surveys with a unique code or direct link. Control access with public or private settings.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-white">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold" style="color: #004179;">Recent Surveys</h2>
                <p class="text-gray-500 mt-2">Check out the latest surveys from our community</p>
            </div>
            @if($recentSurveys->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($recentSurveys as $survey)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                            <h3 class="font-semibold text-lg mb-2 line-clamp-1" style="color: #004179;">{{ $survey->title }}</h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $survey->description ?? 'No description' }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400">by {{ $survey->user->name ?? 'Anonymous' }}</span>
                                <a href="{{ route('surveys.show', $survey->code) }}"
                                    class="text-sm font-medium hover:underline" style="color: #004179;">
                                    Take Survey &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400">No surveys yet. Be the first to create one!</p>
            @endif
        </div>
    </section>
    <section class="py-20 bg-gray-50">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold" style="color: #004179;">Popular Surveys</h2>
                <p class="text-gray-500 mt-2">Most responded surveys by the community</p>
            </div>
            @if($popularSurveys->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($popularSurveys as $survey)
                        <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                            <h3 class="font-semibold text-lg mb-2 line-clamp-1" style="color: #004179;">{{ $survey->title }}</h3>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $survey->description ?? 'No description' }}</p>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-xs px-2 py-0.5 rounded-full text-white" style="background-color: #004179;">
                                    {{ $survey->responses_count }} responses
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400">by {{ $survey->user->name ?? 'Anonymous' }}</span>
                                <a href="{{ route('surveys.show', $survey->code) }}"
                                    class="text-sm font-medium hover:underline" style="color: #004179;">
                                    Take Survey &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400">No surveys yet. Be the first to create one!</p>
            @endif
        </div>
    </section>

</x-layout>
