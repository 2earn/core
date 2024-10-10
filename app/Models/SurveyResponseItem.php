<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SurveyResponseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'surveyResponse_id',
        'surveyQuestion_id',
        'surveyQuestionChoice_id',
    ];

    public function surveyResponse(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'surveyResponse_id', 'id');
    }

    public function surveyQuestion(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'surveyQuestion_id', 'id');
    }

    public function serveyQuestionsChoice(): HasOne
    {
        return $this->hasOne(SurveyQuestionChoice::class, 'surveyQuestionChoice_id');
    }
}
