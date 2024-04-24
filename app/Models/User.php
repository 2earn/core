<?php

namespace App\Models;

use Core\Enum\StatusRequst;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'purchasesNumber',
        'idLastUpline',
        'idReservedUpline',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function hasIdetificationReques()
    {
        $idUser= $this->idUser;
        $identificationRequest = DB::table('identificationuserrequest')
            ->where('idUser',$idUser)
            ->where('status',StatusRequst::EnCours)
            ->first();
        if($identificationRequest){
            return 1;
        }else{
            return 0;
        }
    }
}
