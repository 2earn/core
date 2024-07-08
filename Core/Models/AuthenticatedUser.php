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
            ->where('idUser', $this->idUser)
            ->where('status', StatusRequst::EnCours)
            ->first();
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
