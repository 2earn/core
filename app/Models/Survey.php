<?php

namespace App\Models;

use Core\Enum\StatusSurvey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'enabled',
        'published',
        'status',
        'showAttchivementChrono',
        'showAttchivementPourcentage',
        'showAfterArchiving',
        'startDate',
        'endDate',
        'enableDate',
        'disableDate',
        'publishDate',
        'unpublishDate',
        'openDate',
        'disableDate',
        'closeDate',
        'archivedDate',
        'goals',
        'updatable',
        'showResult',
        'commentable',
        'likable',
        'description',
        'disabledBtnDescription',
    ];

    public function surveyResponse()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function targets()
    {
        return $this->morphToMany(Target::class, 'targetable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public static function enable($id)
    {
        Survey::where('id', $id)->update(['enabled' => true, 'enableDate' => Carbon::now()]);
    }

    public static function disable($id)
    {
        Survey::where('id', $id)->update(['enabled' => false, 'disabledate' => Carbon::now()]);
    }

    public static function publish($id)
    {
        Survey::where('id', $id)->update(['published' => true, 'publishDate' => Carbon::now()]);
    }

    public static function unpublish($id)
    {
        Survey::where('id', $id)->update(['published' => false, 'unpublishDate' => Carbon::now()]);
    }

    public static function open($id)
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::OPEN->value, 'openDate' => Carbon::now()]);
    }

    public static function close($id)
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::CLOSED->value, 'openDate' => Carbon::now()]);
    }

    public static function archive($id)
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::ARCHIVED->value, 'openDate' => Carbon::now()]);
    }

}
