<x-layout title="Browse Surveys - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold" style="color: #004179;">Browse Surveys</h1>
                <p class="text-gray-500 mt-1">Discover and participate in public surveys</p>
            </div>
            <form action="{{ route('surveys.searchByCode') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="code" placeholder="Enter survey code" maxlength="7"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:border-transparent uppercase tracking-wider w-40"
                    required>
                <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 cursor-pointer"
                    style="background-color: #004179;">
                    Search
                </button>
            </form>
        </div>
        @if($surveys->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($surveys as $survey)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 line-clamp-1" style="color: #004179;">{{ $survey->title }}</h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $survey->description ?? 'No description provided.' }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($survey->requires_login)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Requires Login</span>
                                @endif
                                @if($survey->allow_multiple)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Multiple Responses</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>by {{ $survey->user->name ?? 'Anonymous' }}</span>
                                <span>{{ $survey->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 px-6 py-3 bg-gray-50">
                            @if($survey->requires_login && !auth()->check())
                                <a href="{{ route('login') }}"
                                    class="block text-center text-sm font-semibold py-2 rounded-lg text-white transition hover:opacity-90"
                                    style="background-color: #004179;">
                                    Login to Take Survey
                                </a>
                            @else
                                <a href="{{ route('surveys.show', $survey->code) }}"
                                    class="block text-center text-sm font-semibold py-2 rounded-lg text-white transition hover:opacity-90"
                                    style="background-color: #004179;">
                                    Take Survey
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $surveys->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500">No surveys available</h3>
                <p class="text-gray-400 mt-1">Check back later or create your own!</p>
            </div>
        @endif
    </div>
</x-layout>
