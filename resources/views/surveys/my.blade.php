<x-layout title="My Surveys - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold" style="color: #004179;">My Surveys</h1>
                <p class="text-gray-500 mt-1">Manage your created surveys</p>
            </div>
            <a href="{{ route('surveys.create') }}"
                class="px-6 py-2.5 rounded-lg font-semibold text-sm transition hover:opacity-90"
                style="background-color: #f3c404; color: #004179;">
                + Create Survey
            </a>
        </div>
        @if($surveys->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($surveys as $survey)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-lg line-clamp-1" style="color: #004179;">{{ $survey->title }}</h3>
                                @if($survey->is_active)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 shrink-0 ml-2">Active</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 shrink-0 ml-2">Inactive</span>
                                @endif
                            </div>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $survey->description ?? 'No description provided.' }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if(!$survey->is_public)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Private</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Public</span>
                                @endif
                                @if($survey->requires_login)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Requires Login</span>
                                @endif
                                @if($survey->allow_multiple)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Multiple</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-400 mb-2">
                                <span>{{ $survey->responses_count }} responses</span>
                                <span>Code: <span class="font-mono font-medium text-gray-600">{{ $survey->code }}</span></span>
                            </div>
                            <div class="text-xs text-gray-400">
                                Created {{ $survey->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="border-t border-gray-100 px-6 py-3 bg-gray-50">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('surveys.results', $survey->code) }}"
                                    class="flex-1 text-center text-xs font-semibold py-2 rounded-lg transition hover:opacity-90 text-white"
                                    style="background-color: #004179;">
                                    Results
                                </a>
                                <button onclick="copyLink('{{ route('surveys.show', $survey->code) }}')"
                                    class="flex-1 text-center text-xs font-semibold py-2 rounded-lg border transition hover:bg-gray-100 cursor-pointer"
                                    style="color: #004179; border-color: #004179;">
                                    Copy Link
                                </button>
                                <form method="POST" action="{{ route('surveys.toggleActive', $survey) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full text-xs font-semibold py-2 rounded-lg border transition hover:bg-gray-100 cursor-pointer {{ $survey->is_active ? 'text-amber-600 border-amber-300' : 'text-green-600 border-green-300' }}">
                                        {{ $survey->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('surveys.destroy', $survey) }}" class="shrink-0"
                                    onsubmit="return confirm('Are you sure you want to delete this survey?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs font-semibold py-2 px-3 rounded-lg border border-red-300 text-red-600 transition hover:bg-red-50 cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500">No surveys created yet</h3>
                <p class="text-gray-400 mt-1">Create your first survey and start collecting responses.</p>
                <a href="{{ route('surveys.create') }}"
                    class="inline-block mt-4 px-6 py-2.5 rounded-lg font-semibold text-sm transition hover:opacity-90"
                    style="background-color: #f3c404; color: #004179;">
                    + Create Survey
                </a>
            </div>
        @endif
    </div>

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                showToast('Link copied to clipboard!');
            });
        }

        function showToast(message) {
            const existing = document.getElementById('toaster');
            if (existing) existing.remove();

            const div = document.createElement('div');
            div.id = 'toaster';
            div.className = 'fixed top-5 right-5 z-50';
            div.innerHTML = `<div class="flex items-center gap-3 px-5 py-3 rounded-lg shadow-lg text-white border border-white/30" style="background-color: #004179;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>${message}</span>
            </div>`;
            document.body.appendChild(div);

            setTimeout(() => {
                div.style.transition = 'opacity 0.5s';
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 500);
            }, 3000);
        }
    </script>
</x-layout>
