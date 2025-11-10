<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    const IMAGE_TYPE_MAIN = 'main';

    use HasFactory, HasAuditing;

    protected $fillable = ['title', 'enabled', 'content', 'published_at',
        'created_by',
        'updated_by',
    ];
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
    public function hashtags()
    {
        return $this->morphToMany(Hashtag::class, 'hashtagable');
    }
}
