<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'survey_id',
    ];

    public function serveyQuestions()
    {
        return $this->hasMany(SurveyQuestionChoice::class,'question_id');
    }
    public function servey()
    {
        return $this->belongsTo(Survey::class, 'question_id');
    }
}
