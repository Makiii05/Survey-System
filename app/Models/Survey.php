<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';

    protected $fillable = [
        'user_id',
        'code',
        'title',
        'description',
        'is_active',
        'is_public',
        'requires_login',
        'allow_multiple',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            do {
                $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7));
            } while (self::where('code', $code)->exists());

            $survey->code = $code;
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_id');
    }
}
