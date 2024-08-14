<?php

namespace Core\Models;

use Core\Enum\StatusRequest;
use Illuminate\Support\Facades\DB;

class  AuthenticatedUser
{


    protected $casts = [
        'iden_notif' => 'boolean',
    ];

    public function hasIdentificationRequest()
    {
        $identificationRequest = DB::table('identificationuserrequest')
            ->where('idUser', $this->idUser);

        $identificationRequest = $identificationRequest->where(function ($query) {
            $query->where('status', '=', StatusRequest::InProgressNational->value)
                ->orWhere('status', '=', StatusRequest::InProgressInternational->value)
                ->orWhere('status', '=', StatusRequest::InProgressGlobal->value);
        });

        return is_null($identificationRequest->first()) ? false : true;
    }


    public function hasImage($type)
    {
        return file_exists('uploads/profiles/' . $type . '-id-image' . $this->idUser . '.png') ? 1 : 0;
    }

    public function hasFrontImage()
    {
        return $this->hasImage('front');
    }

    public function hasBackImage()
    {
        return $this->hasImage('back');
    }

    public function hasInternationalIdentity()
    {
        return $this->hasImage('international');
    }
}
