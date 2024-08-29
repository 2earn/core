<?php

namespace App\Models;

use Core\Enum\StatusRequest;
use Core\Models\identificationuserrequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function hasIdentificationRequest()
    {
        $idUser = $this->idUser;
        $requestIdentification = identificationuserrequest::where('idUser', $idUser);
        $requestIdentification = $requestIdentification->where(function ($query) {
            $query->where('status', '=', StatusRequest::InProgressNational->value)
                ->orWhere('status', '=', StatusRequest::InProgressInternational->value);
        });

        return is_null($requestIdentification->get()->first()) ? false : true;
    }

    public function surveyResponse()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
