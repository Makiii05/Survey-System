<x-layout title="Results: {{ $survey->title }} - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold" style="color: #004179;">{{ $survey->title }}</h1>
                <p class="text-gray-500 mt-1">{{ $survey->responses_count }} total responses</p>
            </div>
            <a href="{{ route('surveys.my') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to My Surveys</a>
        </div>

        @if($survey->responses_count === 0)
            <div class="text-center py-20">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-500">No responses yet</h3>
                <p class="text-gray-400 mt-1">Share your survey to start collecting responses.</p>
            </div>
        @else
            @php $questionNumber = 0; @endphp
            @foreach($survey->sections as $section)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-4">
                    <h2 class="text-lg font-semibold" style="color: #004179;">{{ $section->title }}</h2>
                    @if($section->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $section->description }}</p>
                    @endif
                    <hr class="my-3 text-slate-400">
                    @foreach($section->questions as $question)
                        @php $questionNumber++; @endphp
                        @php
                            $typesWithOptions = ['radio', 'checkbox', 'dropdown'];
                        @endphp
                        <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4">
                            @if(in_array($question->question_type, $typesWithOptions))
                                @php
                                    $chartData = [];
                                    foreach ($question->options as $option) {
                                        $chartData[$option->id] = [
                                            'label' => $option->option_text,
                                            'value' => 0,
                                        ];
                                    }
                                    foreach ($question->answers as $answer) {
                                        foreach ($answer->answerOptions as $answerOption) {
                                            if (isset($chartData[$answerOption->option_id])) {
                                                $chartData[$answerOption->option_id]['value']++;
                                            }
                                        }
                                    }
                                    $chartData = array_values($chartData);
                                @endphp
                                <div data-survey-chart data-chart-data='@json($chartData)' data-chart-default="bar">
                            @endif
                            <h3 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                                <span class="flex-1">
                                    {{ $questionNumber }}. {{ $question->question_text }}
                                    <span class="text-xs text-gray-400 ml-2">({{ ucfirst($question->question_type) }})</span>
                                </span>
                                @if (in_array($question->question_type, $typesWithOptions))
                                <button type="button" data-chart-type="bar" onclick="window.toggleSurveyChart(this, 'bar')" aria-pressed="true" title="Bar chart" class="p-1.5 rounded transition-colors flex items-center justify-center" style="background-color: #004179; color: #ffffff; cursor: pointer;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h10"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18h7"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4v16"/>
                                    </svg>
                                </button>
                                <button type="button" data-chart-type="pie" onclick="window.toggleSurveyChart(this, 'pie')" aria-pressed="false" title="Pie chart" class="p-1.5 rounded transition-colors flex items-center justify-center" style="background-color: #e2e8f0; color: #475569; cursor: pointer;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a9 9 0 100 18 9 9 0 000-18z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3v9h9"/>
                                    </svg>
                                </button>
                                @endif
                            </h3>
    
    
                            @if(in_array($question->question_type, $typesWithOptions))
                                <div class="mt-4 space-y-4">
                                    <div id="chart-question-{{ $question->id }}" class="min-h-80 rounded-xl border border-gray-100 bg-gray-50/70 p-2" data-chart-container></div>
                                </div>
                                </div>
                            @elseif($question->question_type === 'scale')
                                @php
                                    $scaleCounts = array_fill(1, 10, 0);
                                    $totalAnswers = $question->answers->count();
                                    foreach ($question->answers as $answer) {
                                        $val = (int) $answer->answer_text;
                                        if ($val >= 1 && $val <= 10) {
                                            $scaleCounts[$val]++;
                                        }
                                    }
                                @endphp
                                <div class="flex items-end gap-1 h-24">
                                    @for($i = 1; $i <= 10; $i++)
                                        @php
                                            $pct = $totalAnswers > 0 ? round(($scaleCounts[$i] / $totalAnswers) * 100) : 0;
                                            $height = max($pct, 4);
                                        @endphp
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <span class="text-xs text-gray-400">{{ $scaleCounts[$i] }}</span>
                                            <div class="w-full rounded-t" style="height: {{ $height }}%; background-color: #004179;"></div>
                                            <span class="text-xs text-gray-500">{{ $i }}</span>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                @php
                                    $totalAnswers = $question->answers->count();
                                    $textCounts = [];
                                    foreach ($question->answers as $answer) {
                                        $text = $answer->answer_text ?: '—';
                                        $key = strtolower(trim($text));
                                        if (!isset($textCounts[$key])) {
                                            $textCounts[$key] = ['text' => $text, 'count' => 0];
                                        }
                                        $textCounts[$key]['count']++;
                                    }
                                    uasort($textCounts, fn($a, $b) => $b['count'] - $a['count']);
                                @endphp
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    @foreach($textCounts as $item)
                                        <div class="bg-gray-50 rounded-lg px-4 py-2 text-sm text-gray-700 flex items-center justify-between">
                                            <span>{{ $item['text'] }}</span>
                                            @php
                                                $pct = $totalAnswers > 0 ? round(($item['count'] / $totalAnswers) * 100) : 0;
                                            @endphp
                                            <span class="text-gray-400 text-xs ml-2">{{ $item['count'] }} ({{ $pct }}%)</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            @endforeach
        @endif
    </div>
</x-layout>
