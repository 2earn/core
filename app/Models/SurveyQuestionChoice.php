<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'title',
    ];

    public function Question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
