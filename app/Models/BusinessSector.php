<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSector extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];
    const IMAGE_TYPE_THUMBNAILS = 'thumbnails';
    const IMAGE_TYPE_LOGO = 'logo';

    const DEFAULT_IMAGE_TYPE_LOGO = 'public/uploads/business-sectors/thumbnails/simple logo for business sector.png';
    const DEFAULT_IMAGE_TYPE_THUMB = 'public/uploads/business-sectors/thumbnails/simple business sector logo thumbnail.png';

    public function thumbnailsImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_THUMBNAILS);
    }

    public function logoImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_LOGO);
    }

}
