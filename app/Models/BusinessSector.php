<?php

namespace App\Models;

use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class BusinessSector extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'description',
        'color',
        'created_by',
        'updated_by',
    ];
    const IMAGE_TYPE_THUMBNAILS = 'thumbnails';
    const IMAGE_TYPE_THUMBNAILS_HOME = 'thumbnails-home';
    const IMAGE_TYPE_LOGO = 'logo';

    const DEFAULT_IMAGE_TYPE_LOGO = 'resources/images/business-sectors/logo/simple-logo-for-business-sector.png';
    const DEFAULT_IMAGE_TYPE_THUMB = 'resources/images/business-sectors/thumbnails/simple-business-sector-logo-thumbnail.png';
    const DEFAULT_IMAGE_TYPE_THUMB_HOME = 'resources/images/business-sectors/home/simple-business-sector-thumbnail-home.png';

    public function thumbnailsImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_THUMBNAILS);
    }

    public function thumbnailsHomeImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_THUMBNAILS_HOME);
    }

    public function logoImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_LOGO);
    }

    /**
     * Get all images for the business sector (polymorphic relationship)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function platforms()
    {
        return $this->hasMany(Platform::class);
    }
}
