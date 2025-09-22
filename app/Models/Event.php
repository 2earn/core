<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Add your fillable fields here
    protected $fillable = ['title', 'enabled', 'content', 'published_at', 'start_at', 'end_at'];
    protected $casts = [
        'published_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // Example: If you want to add image relation like News
    const IMAGE_TYPE_MAIN = 'main';
    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_MAIN);
    }
}
