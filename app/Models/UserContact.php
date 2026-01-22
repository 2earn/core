<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class UserContact extends Model
{
    use HasAuditing;

    protected $table = 'user_contacts';
    public $timestamps = true;

    protected $fillable = [
        'idUser',
        'name',
        'lastName',
        'mobile',
        'availablity',
        'disponible',
        'fullphone_number',
        'phonecode',
        'notif_sms',
        'notif_email',
        'reserved_at',
        'created_by',
        'updated_by',
    ];


    public function checkavailablity()
    {

    }
}
