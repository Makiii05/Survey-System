<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function home()
    {
        $recentSurveys = Survey::active()->public()->with('user')->latest()->take(4)->get();
        $popularSurveys = Survey::active()->public()->with('user')->withCount('responses')->orderByDesc('responses_count')->take(4)->get();
        
        return view('home', compact('recentSurveys', 'popularSurveys'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $surveysCreated = $user->surveys()->count();
        $totalResponses = SurveyResponse::whereIn('survey_id', $user->surveys()->pluck('id'))->count();
        $surveysAnswered = $user->surveyResponses()->count();
        
        $recentSurveys = $user->surveys()->withCount('responses')->latest()->take(5)->get();
        
        return view('surveys.dashboard', compact('surveysCreated', 'totalResponses', 'surveysAnswered', 'recentSurveys'));
    }

    public function browse(Request $request)
    {
        $surveys = Survey::active()->public()->with('user')->latest()->paginate(12);
        
        return view('surveys.browse', compact('surveys'));
    }

    public function searchByCode(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:7']]);
        $survey = Survey::findByCode($request->code);
        
        if (!$survey) {
            return back()->with('error', 'No survey found with that code.');
        }
        if (!$survey->is_active) {
            return back()->with('error', 'This survey is no longer active.');
        }

        return redirect()->route('surveys.show', $survey->code);
    }

    public function mySurveys()
    {
        $surveys = Auth::user()->surveys()->withCount('responses')->latest()->paginate(12);

        return view('surveys.my', compact('surveys'));
    }

    public function toggleActive(Survey $survey)
    {
        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        $survey->update(['is_active' => !$survey->is_active]);
        $status = $survey->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Survey {$status} successfully.");
    }

    public function destroy(Survey $survey)
    {
        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        $survey->delete();

        return back()->with('success', 'Survey deleted successfully.');
    }

    public function create()
    {
        return view('surveys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sections_json' => ['required', 'string'],
        ]);

        $sectionsData = json_decode($request->sections_json, true);

        if (!is_array($sectionsData) || count($sectionsData) === 0) {
            return back()->with('error', 'Please add at least one section with questions.')->withInput();
        }

        $hasAtLeastOneQuestion = collect($sectionsData)->contains(function ($sectionData) {
            return !empty($sectionData['questions']) && is_array($sectionData['questions']);
        });

        if (!$hasAtLeastOneQuestion) {
            return back()->with('error', 'Please add at least one question.')->withInput();
        }

        DB::transaction(function () use ($request, $sectionsData) {
            $survey = Survey::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'is_public' => $request->has('is_public'),
                'allow_multiple' => $request->has('allow_multiple'),
                'requires_login' => $request->has('requires_login'),
            ]);

            foreach ($sectionsData as $sectionIndex => $sectionData) {
                $questions = $sectionData['questions'] ?? [];

                if (!is_array($questions) || count($questions) === 0) {
                    continue;
                }

                $section = $survey->sections()->create([
                    'title' => $sectionData['title'] ?? 'Untitled Section',
                    'description' => $sectionData['description'] ?? null,
                    'sort_order' => $sectionIndex,
                ]);

                foreach ($questions as $questionIndex => $qData) {
                    $question = $section->questions()->create([
                        'question_text' => $qData['text'],
                        'question_type' => $qData['type'],
                        'is_required' => $qData['required'] ?? false,
                        'sort_order' => $questionIndex,
                    ]);

                    if (!empty($qData['options'])) {
                        foreach ($qData['options'] as $optIndex => $optText) {
                            $question->options()->create([
                                'option_text' => $optText,
                                'sort_order' => $optIndex,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('surveys.my')->with('success', 'Survey created successfully!');
    }

    public function show($code)
    {
        $survey = Survey::where('code', $code)
            ->where('is_active', true)
            ->with([
                'user',
                'sections' => function ($query) {
                    $query->orderBy('sort_order')->with([
                        'questions' => function ($questionQuery) {
                            $questionQuery->orderBy('sort_order')->with([
                                'options' => function ($optionQuery) {
                                    $optionQuery->orderBy('sort_order');
                                },
                            ]);
                        },
                    ]);
                },
            ])
            ->firstOrFail();

        if (Auth::check() && $survey->user_id === Auth::id()) {
            return redirect()->route('surveys.results', $survey->code);
        }

        if ($survey->requires_login && !Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to take this survey.');
        }

        return view('surveys.show', compact('survey'));
    }

    public function results($code)
    {
        $survey = Survey::where('code', $code)
            ->with([
                'user',
                'sections' => function ($query) {
                    $query->orderBy('sort_order')->with([
                        'questions' => function ($questionQuery) {
                            $questionQuery->orderBy('sort_order')->with([
                                'options' => function ($optionQuery) {
                                    $optionQuery->orderBy('sort_order');
                                },
                                'answers.answerOptions',
                            ]);
                        },
                    ]);
                },
            ])
            ->withCount('responses')
            ->firstOrFail();

        if ($survey->user_id !== Auth::id()) {
            abort(403);
        }

        return view('surveys.results', compact('survey'));
    }

    public function submit(Request $request, $code)
    {
        $survey = Survey::where('code', $code)
            ->where('is_active', true)
            ->with([
                'sections' => function ($query) {
                    $query->orderBy('sort_order')->with([
                        'questions' => function ($questionQuery) {
                            $questionQuery->orderBy('sort_order')->with('options');
                        },
                    ]);
                },
            ])
            ->firstOrFail();

        $questions = $survey->sections->flatMap(function ($section) {
            return $section->questions;
        });

        if ($survey->requires_login && !Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit this survey.');
        }

        $rules = [
            'respondent_email' => ['required', 'email', 'max:255'],
        ];
        foreach ($questions as $question) {
            if ($question->is_required) {
                $rules["answers.{$question->id}"] = ['required'];
            }
        }
        $request->validate($rules);

        if (!$survey->allow_multiple) {
            $existing = SurveyResponse::where('survey_id', $survey->id)
                ->where('respondent_email', $request->respondent_email)
                ->exists();
            if ($existing) {
                return back()->withInput()->with('error', 'A response with this email has already been submitted.');
            }
        }

        DB::transaction(function () use ($request, $survey, $questions) {
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,
                'user_id' => Auth::id(),
                'respondent_email' => $request->respondent_email,
            ]);

            foreach ($questions as $question) {
                $value = $request->input("answers.{$question->id}");
                if ($value === null) continue;

                $typesWithOptions = ['radio', 'checkbox', 'dropdown'];

                if ($question->question_type === 'checkbox' && is_array($value)) {
                    $answer = $response->answers()->create([
                        'question_id' => $question->id,
                        'answer_text' => '',
                    ]);
                    foreach ($value as $optionId) {
                        if ($question->options->contains('id', (int) $optionId)) {
                            $answer->answerOptions()->create(['option_id' => $optionId]);
                        }
                    }
                } elseif (in_array($question->question_type, ['radio', 'dropdown'])) {
                    $answer = $response->answers()->create([
                        'question_id' => $question->id,
                        'answer_text' => '',
                    ]);
                    if ($question->options->contains('id', (int) $value)) {
                        $answer->answerOptions()->create(['option_id' => $value]);
                    }
                } else {
                    $response->answers()->create([
                        'question_id' => $question->id,
                        'answer_text' => $value,
                    ]);
                }
            }
        });

        return redirect()->route('surveys.browse')->with('success', 'Your response has been submitted. Thank you!');
    }
}
