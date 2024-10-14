<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    const MAX_PHOTO_ALLAWED_SIZE = 2048000;
    const IMAGE_PROFILE_PATH = 'uploads/profiles/';
    const IMAGE_PREFIX_PROFILE = 'profile-image-';
    const IMAGE_PREFIX_FRONT = 'front-id-image';
    const IMAGE_PREFIX_BACK = 'back-id-image';
    const IMAGE_PREFIX_INTERNATIONAL = 'international-id-image';

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
