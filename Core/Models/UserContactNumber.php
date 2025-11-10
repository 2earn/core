<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class UserContactNumber extends Model
{
    use HasAuditing;

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
