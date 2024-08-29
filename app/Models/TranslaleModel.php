<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslaleModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'valueFr', 'valueEn'];

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
        return strtoupper(self::getDataFromName($var)[1]);
    }

    public static function getIdFromName($var)
    {
        return self::getDataFromName($var)[2];
    }

}
