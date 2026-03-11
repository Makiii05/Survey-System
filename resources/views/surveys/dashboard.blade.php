<x-layout title="Dashboard - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold" style="color: #004179;">Dashboard</h1>
                <p class="text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}</p>
            </div>
            <a href="{{ route('surveys.create') }}"
                class="px-6 py-2.5 rounded-lg font-semibold text-sm transition hover:opacity-90"
                style="background-color: #f3c404; color: #004179;">
                + Create Survey
            </a>
        </div>

        {{-- Stats Widgets --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #004179;">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Surveys Created</p>
                        <p class="text-2xl font-bold" style="color: #004179;">{{ $surveysCreated }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #f3c404;">
                        <svg class="w-6 h-6" style="color: #004179;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Responses</p>
                        <p class="text-2xl font-bold" style="color: #004179;">{{ $totalResponses }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Surveys Answered</p>
                        <p class="text-2xl font-bold" style="color: #004179;">{{ $surveysAnswered }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Surveys Table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold" style="color: #004179;">Recent Surveys</h2>
            </div>
            @if($recentSurveys->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Responses</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentSurveys as $survey)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-800 line-clamp-1">{{ $survey->title }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-gray-600">{{ $survey->code }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $survey->responses_count }}</td>
                                <td class="px-6 py-4">
                                    @if($survey->is_active)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $survey->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('surveys.results', $survey->code) }}" class="text-sm font-medium hover:underline" style="color: #004179;">View Results</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">No surveys created yet.</p>
                    <a href="{{ route('surveys.create') }}" class="text-sm font-medium hover:underline mt-2 inline-block" style="color: #004179;">Create your first survey</a>
                </div>
            @endif
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('surveys.my') }}" class="text-sm text-gray-500 hover:text-gray-700">View all my surveys &rarr;</a>
        </div>
    </div>
</x-layout>
