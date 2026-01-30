<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class UserContactNumber extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'usercontactnumber';
    public $timestamps = true;

    protected $fillable = [
        'idUser',
        'mobile',
        'codeP',
        'active',
        'isoP',
        'fullNumber',
        'isID',
        'created_by',
        'updated_by',
    ];
}
