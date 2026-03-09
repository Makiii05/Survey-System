<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'response_id',
        'question_id',
        'answer_text',
    ];

    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answerOptions()
    {
        return $this->hasMany(AnswerOption::class, 'answer_id');
    }

    public function selectedOptions()
    {
        return $this->belongsToMany(QuestionOption::class, 'answer_options', 'answer_id', 'option_id');
    }
}
