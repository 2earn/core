<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function news()
    {
        return $this->morphedByMany(News::class, 'hashtagable');
    }
}
