<?php

namespace App\Services\Communication;

use App\Enums\StatusSurvey;
use App\Models\Event;
use App\Models\News;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Communication
{
    public function __construct()
    {
    }


    public static function duplicateSurvey($id)
    {
        try {
            DB::beginTransaction();
            $original = Survey::findOrFail($id);
            $duplicate = $original->replicate();
            $duplicate->name = $original->name . ' (Copy)';
            $duplicate->description = $original->description . ' (Copy)';
            $duplicate->enabled = false;
            $duplicate->status = StatusSurvey::NEW->value;
            $duplicate->created_at = now();
            $duplicate->updated_at = now();
            $duplicate->save();
            createTranslaleModel($duplicate, 'name', $duplicate->name);
            createTranslaleModel($duplicate, 'description', $duplicate->description);

            if ($duplicate->targets->isEmpty()) {
                $duplicate->targets()->attach([$original->targets->first()]);
            }

            $originalQuestion = $original->question()->first();
            $duplicateQuestion = $originalQuestion->replicate();
            $duplicateQuestion->survey_id = $duplicate->id;
            $duplicateQuestion->content = $originalQuestion->content;
            $duplicateQuestion->save();

            createTranslaleModel($duplicateQuestion, 'content', $duplicateQuestion->content);

            $originalQuestionChoices = $originalQuestion->serveyQuestionChoice()->get();
            foreach ($originalQuestionChoices as $originalQuestionChoice) {
                $duplicateQuestionChoice = $originalQuestionChoice->replicate();
                $duplicateQuestionChoice->title = $originalQuestionChoice->title;
                $duplicateQuestionChoice->question_id = $duplicateQuestion->id;
                $duplicateQuestionChoice->save();
                createTranslaleModel($duplicateQuestionChoice, 'title', $duplicateQuestionChoice->title);
            }
            DB::commit();
            return $duplicate;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public static function duplicateNews($id)
    {
        $original = News::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->content = $original->content . ' (Copy)';
        $duplicate->enabled = false;
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();

        createTranslaleModel($duplicate, 'title', $duplicate->title);
        createTranslaleModel($duplicate, 'content', $duplicate->content);

        if (!is_null($original->mainImage)) {
            $image = $original->mainImage->replicate();
            $image->imageable_id = $duplicate->id;
            $image->save();
        }
        return $duplicate;
    }

    public static function duplicateEvent($id)
    {
        $original = Event::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->content = $original->content . ' (Copy)';
        $duplicate->enabled = false;
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();

        createTranslaleModel($duplicate, 'title', $duplicate->title);
        createTranslaleModel($duplicate, 'content', $duplicate->content);

        if (!is_null($original->mainImage)) {
            $image = $original->mainImage->replicate();
            $image->imageable_id = $duplicate->id;
            $image->save();
        }
        return $duplicate;
    }
}
