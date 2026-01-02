<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class metta_user extends Model
{
    use HasAuditing;

    protected $table = 'metta_users';
    public $timestamps = true;

    protected $fillable = [
        'idUser',
        'arFirstName',
        'arLastName',
        'enFirstName',
        'enLastName',
        'personaltitle',
        'idCountry',
        'childrenCount',
        'birthday',
        'gender',
        'email',
        'secondEmail',
        'idLanguage',
        'nationalID',
        'internationalISD',
        'adresse',
        'idState',
        'note',
        'interests',
        'created_by',
        'updated_by',
    ];
}
