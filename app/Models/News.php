<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    const IMAGE_TYPE_MAIN = 'main';

    use HasFactory;

    protected $fillable = ['title', 'enabled', 'content', 'published_at'];
    protected $casts = ['published_at' => 'datetime'];

    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_MAIN);
    }
    public function likes()
    {
        return $this->morphMany(\App\Models\Like::class, 'likable');
    }
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }
}
