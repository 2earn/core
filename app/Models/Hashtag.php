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
}
