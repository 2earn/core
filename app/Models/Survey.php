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

    public function question()
    {
        return $this->hasOne(SurveyQuestion::class);
    }

    public static function enable($id): bool
    {
        Survey::where('id', $id)->update(['enabled' => true, 'enableDate' => Carbon::now()]);
        return true;
    }

    public static function disable($id, $note): bool
    {
        Survey::where('id', $id)->update(['enabled' => false, 'disabledBtnDescription' => $note, 'disabledate' => Carbon::now()]);
        return true;
    }

    public static function publish($id): bool
    {
        Survey::where('id', $id)->update(['published' => true, 'publishDate' => Carbon::now()]);
        return true;
    }

    public static function unpublish($id): bool
    {
        Survey::where('id', $id)->update(['published' => false, 'unpublishDate' => Carbon::now()]);
        return true;
    }

    public static function open($id): bool
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::OPEN->value, 'openDate' => Carbon::now()]);
        return true;
    }

    public static function close($id): bool
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::CLOSED->value, 'openDate' => Carbon::now()]);
        return true;
    }

    public static function archive($id): bool
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::ARCHIVED->value, 'openDate' => Carbon::now()]);
        return true;
    }
}
