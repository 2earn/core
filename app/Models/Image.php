<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

class Image extends Model
{
    use HasFactory;

    const MAX_PHOTO_ALLAWED_SIZE = 2048000;
    const PHOTO_ALLAWED_EXT = ['png', 'jpg', 'jpeg'];
    protected $fillable = [
        'type',
        'url'
    ];

    public static function validateImage($image)
    {
        if (is_null($image) || gettype($image) != "object") {
            throw new \Exception('Invalid image type');
        }
        if ($image->getSize() > self::MAX_PHOTO_ALLAWED_SIZE) {
            throw new \Exception('You have exceeded the maximum photo size');
        }
        if (!in_array($image->extension(), self::PHOTO_ALLAWED_EXT)) {
            throw new \Exception('Profile photo wrong type');
        }
    }

    public function imageable()
    {
        return $this->morphTo();
    }

}
