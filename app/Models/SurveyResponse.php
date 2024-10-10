<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
    ];

    use HasFactory;

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function surveyResponseItem()
    {
        return $this->hasOne(SurveyResponseItem::class);
    }

    public static function isPaticipated($idUser, $idSurvey)
    {
        return SurveyResponse::where('user_id', $idUser)->where('survey_id', $idSurvey)->exists();
    }
}
