<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class SurveyQuestionChoice extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'question_id',
        'title',
    ];

    public function Question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
