<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class SurveyQuestion extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'content',
        'selection',
        'maxResponse',
        'survey_id',
    ];

    public function serveyQuestionChoice()
    {
        return $this->hasMany(SurveyQuestionChoice::class, 'question_id');
    }

    public function servey()
    {
        return $this->belongsTo(Survey::class, 'question_id');
    }
}
