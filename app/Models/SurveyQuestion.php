<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'selection',
        'maxResponse',
        'survey_id',
    ];

    public function serveyQuestionChoice(): HasMany
    {
        return $this->hasMany(SurveyQuestionChoice::class, 'question_id');
    }

    public function servey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'question_id');
    }
}
