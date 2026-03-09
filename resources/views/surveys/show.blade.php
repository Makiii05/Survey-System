<x-layout title="{{ $survey->title }} - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h1 class="text-3xl font-bold" style="color: #004179;">{{ $survey->title }}</h1>
            @if($survey->description)
                <p class="text-gray-500 mt-2">{{ $survey->description }}</p>
            @endif
            <div class="flex flex-wrap gap-3 mt-3 text-xs">
                <span class="text-gray-400">by {{ $survey->user->name ?? 'Anonymous' }}</span>
                @if($survey->requires_login)
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Requires Login</span>
                @endif
                @if($survey->allow_multiple)
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Multiple Responses</span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('surveys.submit', $survey->code) }}">
            @csrf

            <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4">
                <label class="block font-medium text-gray-800 mb-3">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="respondent_email" value="{{ old('respondent_email') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="your@email.com">
                @error('respondent_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @foreach($survey->questions as $index => $question)
                <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4">
                    <label class="block font-medium text-gray-800 mb-3">
                        {{ $index + 1 }}. {{ $question->question_text }}
                        @if($question->is_required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>

                    @switch($question->question_type)
                        @case('text')
                            <input type="text" name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="Your answer"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('textarea')
                            <textarea name="answers[{{ $question->id }}]" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="Your answer"
                                {{ $question->is_required ? 'required' : '' }}></textarea>
                            @break

                        @case('radio')
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                            class="w-4 h-4" {{ $question->is_required ? 'required' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('checkbox')
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}"
                                            class="w-4 h-4 rounded border-gray-300">
                                        <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('dropdown')
                            <select name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                {{ $question->is_required ? 'required' : '' }}>
                                <option value="">Select an option</option>
                                @foreach($question->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->option_text }}</option>
                                @endforeach
                            </select>
                            @break

                        @case('date')
                            <input type="date" name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('time')
                            <input type="time" name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('number')
                            <input type="number" name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="0"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('email')
                            <input type="email" name="answers[{{ $question->id }}]"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="email@example.com"
                                {{ $question->is_required ? 'required' : '' }}>
                            @break

                        @case('scale')
                            <div class="flex items-center gap-2 flex-wrap">
                                @for($i = 1; $i <= 10; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}"
                                            class="hidden peer" {{ $question->is_required ? 'required' : '' }}>
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 peer-checked:text-white peer-checked:border-transparent transition"
                                            style="peer-checked:background-color: #004179;"
                                            onmouseenter="this.style.backgroundColor='#004179'; this.style.color='white';"
                                            onmouseleave="if(!this.previousElementSibling.checked){this.style.backgroundColor=''; this.style.color='';}">
                                            {{ $i }}
                                        </span>
                                    </label>
                                @endfor
                            </div>
                            @break
                    @endswitch

                    @error("answers.{$question->id}")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="px-8 py-2.5 rounded-lg text-white font-semibold transition hover:opacity-90 cursor-pointer"
                    style="background-color: #004179;">
                    Submit Response
                </button>
                <a href="{{ route('surveys.browse') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Browse</a>
            </div>
        </form>
    </div>
</x-layout>
