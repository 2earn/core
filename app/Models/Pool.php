<?php

namespace App\Models;

use Core\Models\countrie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Pool extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'value',
        'max',
        'created_by',
        'updated_by',
    ];

    public function country()
    {
        return $this->hasMany(countrie::class);
    }

}
