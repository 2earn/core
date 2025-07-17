<?php

namespace App\Services\Communication;

use App\Models\News;
use App\Models\Survey;
use App\Models\TranslaleModel;
use Core\Enum\StatusSurvey;

class Communication
{
    public function __construct()
    {
    }


    public static function duplicateSurvey($id)
    {
        $original = Survey::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->name = $original->name . ' (Copy)';
        $duplicate->description = $original->description . ' (Copy)';
        $duplicate->enabled = false;
        $duplicate->status = StatusSurvey::NEW->value;
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();
        $translations = ['name', 'description'];

        foreach ($translations as $translation) {
            TranslaleModel::create(
                [
                    'name' => TranslaleModel::getTranslateName($duplicate, $translation),
                    'value' => $duplicate->{$translation} . ' AR',
                    'valueFr' => $duplicate->{$translation} . ' FR',
                    'valueEn' => $duplicate->{$translation} . ' EN',
                    'valueTr' => $duplicate->{$translation} . ' TR',
                    'valueEs' => $duplicate->{$translation} . ' ES',
                    'valueRu' => $duplicate->{$translation} . ' Ru',
                    'valueDe' => $duplicate->{$translation} . ' De',
                ]);
        }


        if ($duplicate->targets->isEmpty()) {
            $duplicate->targets()->attach([$original->targets->first()]);
        }

        $originalQuestion = $original->question()->first();
        $duplicateQuestion = $originalQuestion->replicate();
        $duplicateQuestion->survey_id = $duplicate->id;
        $duplicateQuestion->content = $originalQuestion->content;
        $duplicateQuestion->save();

        TranslaleModel::create(
            [
                'name' => TranslaleModel::getTranslateName($duplicateQuestion, 'content'),
                'value' => $duplicateQuestion->content . ' AR',
                'valueFr' => $duplicateQuestion->content . ' FR',
                'valueEn' => $duplicateQuestion->content . ' EN',
                'valueEs' => $duplicateQuestion->content . ' ES',
                'valueTr' => $duplicateQuestion->content . ' Tr',
                'valueRu' => $duplicateQuestion->content . ' RU',
                'valueDe' => $duplicateQuestion->content . ' DE',
            ]);

        $originalQuestionChoices = $originalQuestion->serveyQuestionChoice()->get();
        foreach ($originalQuestionChoices as $originalQuestionChoice) {
            $duplicateQuestionChoice = $originalQuestionChoice->replicate();
            $duplicateQuestionChoice->title = $originalQuestionChoice->title;
            $duplicateQuestionChoice->question_id = $duplicateQuestion->id;
            $duplicateQuestionChoice->save();
            TranslaleModel::create(
                [
                    'name' => TranslaleModel::getTranslateName($duplicateQuestionChoice, 'title'),
                    'value' => $duplicateQuestionChoice->title . ' AR',
                    'valueFr' => $duplicateQuestionChoice->title . ' FR',
                    'valueEn' => $duplicateQuestionChoice->title . ' EN',
                    'valueEs' => $duplicateQuestionChoice->title . ' ES',
                    'valueTr' => $duplicateQuestionChoice->title . ' Tr',
                    'valueRu' => $duplicateQuestionChoice->title . ' Ru',
                    'valueDe' => $duplicateQuestionChoice->title . ' De',
                ]);
        }
        return $duplicate;
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
        $translations = ['title', 'content'];

        foreach ($translations as $translation) {
            TranslaleModel::create(
                [
                    'name' => TranslaleModel::getTranslateName($duplicate, $translation),
                    'value' => $duplicate->{$translation} . ' AR',
                    'valueFr' => $duplicate->{$translation} . ' FR',
                    'valueEn' => $duplicate->{$translation} . ' EN',
                    'valueTr' => $duplicate->{$translation} . ' TR',
                    'valueEs' => $duplicate->{$translation} . ' ES',
                    'valueRu' => $duplicate->{$translation} . ' Ru',
                    'valueDe' => $duplicate->{$translation} . ' De',
                ]);
        }
        if (!is_null($original->mainImage)) {
            $image = $original->mainImage->replicate();
            $image->imageable_id = $duplicate->id;
            $image->save();
        }
        return$duplicate;
    }
}
