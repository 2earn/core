<?php

namespace App\Models;

use App\Livewire\PlatformIndex;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Item extends Model
{
    use HasFactory, HasAuditing;

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
        'created_by',
        'updated_by',
    ];

    protected $attributes = [
        'stock' => 0,
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

    public function dealChanges()
    {
        return $this->hasMany(DealProductChange::class);
    }

    public function thumbnailsImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_THUMBNAILS);
    }
}
