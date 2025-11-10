<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Share extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'detail',
        'unique_id',
        'item_id'
    ];

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
}
