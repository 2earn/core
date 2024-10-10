<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'surveyResponse_id',
        'surveyQuestion_id',
        'surveyQuestionChoice_id',
    ];

    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponse::class, 'surveyResponse_id', 'id');
    }

    public function surveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class, 'surveyQuestion_id', 'id');
    }

    public function serveyQuestionsChoice()
    {
        return $this->hasOne(SurveyQuestionChoice::class, 'surveyQuestionChoice_id');
    }
}
