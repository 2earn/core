<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class user_earn extends Model
{
    use HasAuditing;

    public $timestamps = true;
    protected $table = 'user_earns';

    protected $fillable = [
        'idUser',
        'name',
        'registred_at',
        'registred_from',
        'idUpline',
        'isSMSSended',
        'activationCode_at',
        'activationCodeValue',
        'activationDone_at',
        'activationDone',
        'KYCIdentified_at',
        'isKYCIdentified',
        'idKYC',
        'password',
        'diallingCode',
        'mobile',
        'fullphone_number',
        'change_to',
        'idCountry',
        'isCountryRepresentative',
        'is_completed',
        'verify_code',
        'email_verified',
        'created_by',
        'updated_by',
    ];

    public function userbalances()
    {
        return $this->hasMany(user_balance::class,'idUser','idUser');
    }
}
