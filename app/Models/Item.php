<?php

namespace App\Models;

use App\Livewire\PlatformIndex;
use Core\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ref',
        'price',
        'discount',
        'discount_2earn',
        'photo_link',
        'description',
        'stock',
        'deal_id',
        'platform_id',
    ];
    const IMAGE_TYPE_THUMBNAILS = 'thumbnails';
    const DEFAULT_IMAGE_TYPE_THUMB = 'resources/images/items/item-thumbnail.png';

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id', 'id');
    }

    public function OrderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
    public function platform()
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }
    public function thumbnailsImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_THUMBNAILS);
    }
}
