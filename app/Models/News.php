<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    const IMAGE_TYPE_MAIN = 'profile';

    use HasFactory;

    protected $fillable = ['title', 'enabled', 'content', 'published_at'];
    protected $casts = ['published_at' => 'datetime'];

    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_MAIN);
    }
}
