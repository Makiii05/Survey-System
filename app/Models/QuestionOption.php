<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'sort_order',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answerOptions()
    {
        return $this->hasMany(AnswerOption::class, 'option_id');
    }
}
