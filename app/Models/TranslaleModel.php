<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class TranslaleModel extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = ['name', 'value', 'valueFr', 'valueEn', 'valueTr', 'valueEs', 'valueRu', 'valueDe'];

    public static function getTranslateName($var, $property)
    {
        $classReflection = new \ReflectionClass($var);
        return $classReflection->getShortName() . '-' . $property . '-' . $var->id;
    }

    public static function getTranslation($var, $property, $other)
    {
        $name = TranslaleModel::getTranslateName($var, $property);
        if (TranslaleModel::where('name', $name)->get()->isNotEmpty()) {
            $column = match (strtoupper(app()->getLocale())) {
                'AR' => 'Value',
                'FR' => 'ValueFR',
                'EN' => 'ValueEN',
                'ES' => 'ValueES',
                'TR' => 'ValueTR',
                'RU' => 'ValueRU',
                'DE' => 'ValueDE',
            };

            return TranslaleModel::where('name', $name)->pluck($column)->first();
        }
        return $other;
    }

    public static function getDataFromName($var)
    {
        return explode('-', $var);
    }

    public static function getClassNameFromName($var)
    {
        return self::getDataFromName($var)[0];
    }

    public static function getPropertyFromName($var)
    {
        return isset(self::getDataFromName($var)[1]) ? strtoupper(self::getDataFromName($var)[1]) : '';
    }

    public static function getIdFromName($var)
    {
        return isset(self::getDataFromName($var)[2]) ? self::getDataFromName($var)[2] : '';
    }

    public static function getLink($var)
    {
        $class = self::getClassNameFromName($var);
        $id = self::getIdFromName($var);

        if ($class == 'Faq') {
            return route('faq_index', ['locale' => app()->getLocale()]);
        }

        if ($class == 'News') {
            return route('news_index', ['locale' => app()->getLocale()]);
        }

        if ($class == 'Survey') {
            return route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $id]);
        }

        if ($class == 'Platform') {
            return route('platform_show', ['locale' => app()->getLocale(), 'id' => $id]);
        }

        if ($class == 'BusinessSector') {
            return route('business_sector_show', ['locale' => app()->getLocale(), 'id' => $id]);
        }

        if ($class == 'SurveyQuestion') {
            $surveyQuestion = SurveyQuestion::find($id);
            if (!is_null($surveyQuestion)) {
                return route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $surveyQuestion->survey_id]);
            }
        }

        if ($class == 'SurveyQuestionChoice') {
            $surveyQuestionChoice = SurveyQuestionChoice::find($id);
            if (!is_null($surveyQuestionChoice)) {

                $surveyQuestion = SurveyQuestion::find($surveyQuestionChoice->question_id);
                if (!is_null($surveyQuestion)) {

                    return route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $surveyQuestion->survey_id]);
                }
            }
        }
        return "#";
    }

}
