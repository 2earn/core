<?php

namespace Core\Models;

use Core\Enum\StatusRequst;
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
        $identificationRequest = $identificationRequest->where(function ($identificationRequest) {
            $identificationRequest->where('status', '=', StatusRequst::EnCoursNational)
                ->orWhere('status', '=', StatusRequst::EnCoursInternational);
        });
        $identificationRequest->first();
        return is_null($identificationRequest) ? false : true;
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
