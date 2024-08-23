<?php

namespace App\Models;

use App\Services\Targeting\Targeting;
use Core\Enum\StatusSurvey;
use Core\Enum\TargetType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

class Survey extends Model
{
    use HasFactory;

    const SUPER_ADMIN_ROLE_NAME = "SUPER ADMIN";
    const DELAY_AFTER_CLOSED = 10;

    protected $fillable = [
        'name',
        'enabled',
        'published',
        'status',
        'show',
        'showAttchivementChrono',
        'showAttchivementGool',
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
        'disabledResult',
        'disabledComment',
        'disabledLike',
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
       return Survey::where('id', $id)->update(['enabled' => true, 'enableDate' => Carbon::now()]);
    }

    public static function disable($id, $note): bool
    {
        return Survey::where('id', $id)->update(['enabled' => false, 'disabledBtnDescription' => $note, 'disabledate' => Carbon::now()]);
    }

    public static function publish($id): bool
    {
        return Survey::where('id', $id)->update(['published' => true, 'publishDate' => Carbon::now()]);
    }

    public static function unpublish($id): bool
    {
        return Survey::where('id', $id)->update(['published' => false, 'unpublishDate' => Carbon::now()]);
    }

    public static function open($id): bool
    {
        return Survey::where('id', $id)->update(['status' => StatusSurvey::OPEN->value, 'openDate' => Carbon::now()]);
    }

    public static function close($id): bool
    {
        return Survey::where('id', $id)->update(['status' => StatusSurvey::CLOSED->value, 'closeDate' => Carbon::now()]);
    }

    public static function archive($id): bool
    {
        Survey::where('id', $id)->update(['status' => StatusSurvey::ARCHIVED->value, 'archivedDate' => Carbon::now()]);
        return true;
    }

    public static function canBeOpened($id): bool
    {
        $survey = Survey::find($id);

        if (!$survey->enabled) {
            throw new \Exception(Lang::get('This is disabled'));
        }
        if ($survey->targets->isEmpty()) {
            throw new \Exception(Lang::get('No target spacified'));
        }

        if (!is_null($survey->question)) {

            if ($survey->question->serveyQuestionChoice()->count() < 2) {
                throw new \Exception(Lang::get('We need more choices for the question'));
            }

        } else {
            throw new \Exception(Lang::get('We need to add the question'));
        }

        return true;
    }

    public function getChronoAttchivement(): int
    {
        $survey = Survey::find($this->id);
        if (is_null($survey->startDate) || is_null($survey->endDate)) {
            return 0;
        }
        $startDate = new \DateTime($survey->startDate);
        $endDate = new \DateTime($survey->endDate);
        $today = new \DateTime();
        if ($startDate > $today) {
            return 0;
        }
        $surveyInterval = $startDate->diff($endDate);
        $startNowInterval = $startDate->diff($today);
        $surveyInterval_h = ($surveyInterval->days * 24) + $surveyInterval->h;
        $startNowInterval_h = ($startNowInterval->days * 24) + $startNowInterval->h;
        if ($today > $endDate) {
            return 100;
        }
        return round($startNowInterval_h / $surveyInterval_h * 100, 2);
    }

    public function getGoolsAttchivement(): int
    {
        $survey = Survey::find($this->id);
        if (!is_null($survey->goals) && $survey->goals > 0) {
            return round($survey->surveyResponse->count() / $survey->goals * 100, 2);
        }
        return 0;
    }


    public function CheckVisibility($idSurvey, $property): bool
    {
        $survey = Survey::find($idSurvey);
        if ($survey->{$property} == TargetType::ALL->value) {
            return true;
        }

        if ($survey->{$property} == TargetType::ADMINS->value && strtoupper(auth()?->user()?->getRoleNames()->first()) == self::SUPER_ADMIN_ROLE_NAME) {
            return true;
        }

        if ($survey->{$property} == TargetType::TARGET->value) {
            return Targeting::isSurveyInTarget($survey, auth()?->user());
        }

        return false;
    }

    public function isLikable(): bool
    {
        return $this->CheckVisibility($this->id, 'likable');
    }

    public function isCommentable(): bool
    {
        return $this->CheckVisibility($this->id, 'commentable');
    }

    public function canShow(): bool
    {
        $survey = Survey::find($this->id);
        $today = new \DateTime();

        if ($survey->status == StatusSurvey::OPEN->value || $survey->status == StatusSurvey::NEW->value) {
            if (!is_null($survey->goals) && $survey->goals > 0 && SurveyResponse::where('survey_id', $this->id)->count() >= $survey->goals) {
                Survey::close($survey->id);
                return false;
            }

            if (is_null($survey->startDate) && is_null($survey->endDate)) {
                return $this->CheckVisibility($this->id, 'show');
            }


            if (!is_null($survey->startDate)) {
                $startDate = new \DateTime($survey->startDate);
                if ($startDate > $today) {
                    return false;
                }
            }

            if (!is_null($survey->endDate)) {
                $endDate = new \DateTime($survey->endDate);
                if ($endDate < $today) {
                    Survey::close($survey->id);
                    return false;
                }
            }

            if (!is_null($survey->startDate) && !is_null($survey->endDate)) {
                $startDate = new \DateTime($survey->startDate);
                $endDate = new \DateTime($survey->endDate);
                if ($startDate < $today && $today < $endDate) {
                    return $this->CheckVisibility($this->id, 'show');
                }
            }
        }
        if ($survey->status == StatusSurvey::CLOSED->value) {

            if (!is_null($survey->closeDate)) {
                $closeDate = new \DateTime($survey->closeDate);

                if ($closeDate->modify('+' . self::DELAY_AFTER_CLOSED . ' day') > $today) {
                    return true;
                }
            }
        }

        return false;

    }

    public function canShowAttchivementGools(): bool
    {
        return $this->CheckVisibility($this->id, 'showAttchivementGool');
    }

    public function canShowAttchivementChrono(): bool
    {
        return $this->CheckVisibility($this->id, 'showAttchivementChrono');
    }

    public function canShowAfterArchiving(): bool
    {
        return $this->CheckVisibility($this->id, 'showAfterArchiving');
    }

    public function canShowResult(): bool
    {
        return $this->CheckVisibility($this->id, 'showResult');
    }

}
