<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TranslaleModel;

class Hashtag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function news()
    {
        return $this->morphedByMany(News::class, 'hashtagable');
    }

    public function getTranslatedName()
    {
        return TranslaleModel::getTranslation($this, 'name', $this->name);
    }

    public function getAllTranslations()
    {
        $trans = TranslaleModel::where('name', TranslaleModel::getTranslateName($this, 'name'))->first();
        return [
            'original' => $this->name,
            'ar' => $trans->value ?? '',
            'fr' => $trans->valueFr ?? '',
            'en' => $trans->valueEn ?? '',
            'es' => $trans->valueEs ?? '',
            'tr' => $trans->valueTr ?? '',
            'ru' => $trans->valueRu ?? '',
            'de' => $trans->valueDe ?? '',
        ];
    }
}
