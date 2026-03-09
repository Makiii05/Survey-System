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
            @foreach($survey->questions as $qIndex => $question)
                <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4">
                    <h3 class="font-medium text-gray-800 mb-4">
                        {{ $qIndex + 1 }}. {{ $question->question_text }}
                        <span class="text-xs text-gray-400 ml-2">({{ ucfirst($question->question_type) }})</span>
                    </h3>

                    @php
                        $typesWithOptions = ['radio', 'checkbox', 'dropdown'];
                    @endphp

                    @if(in_array($question->question_type, $typesWithOptions))
                        @php
                            $totalAnswers = $question->answers->count();
                            $optionCounts = [];
                            foreach ($question->options as $option) {
                                $optionCounts[$option->id] = [
                                    'text' => $option->option_text,
                                    'count' => 0,
                                ];
                            }
                            foreach ($question->answers as $answer) {
                                foreach ($answer->answerOptions as $ao) {
                                    if (isset($optionCounts[$ao->option_id])) {
                                        $optionCounts[$ao->option_id]['count']++;
                                    }
                                }
                            }
                        @endphp
                        <div class="space-y-3">
                            @foreach($optionCounts as $optId => $opt)
                                @php
                                    $pct = $totalAnswers > 0 ? round(($opt['count'] / $totalAnswers) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-gray-700">{{ $opt['text'] }}</span>
                                        <span class="text-gray-400">{{ $opt['count'] }} ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-3">
                                        <div class="h-3 rounded-full" style="width: {{ $pct }}%; background-color: #004179;"></div>
                                    </div>
                                </div>
                            @endforeach
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
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach($question->answers as $answer)
                                <div class="bg-gray-50 rounded-lg px-4 py-2 text-sm text-gray-700">
                                    {{ $answer->answer_text ?: '—' }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</x-layout>
