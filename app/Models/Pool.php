<?php

namespace App\Models;

use Core\Models\countrie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'max',
    ];

    public function country()
    {
        return $this->hasMany(countrie::class);
    }

}
