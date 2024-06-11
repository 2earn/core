<?php

namespace Core\Models;

use Core\Enum\StatusRequst;
use Illuminate\Support\Facades\DB;

class  AuthenticatedUser
{


    protected $casts = [
        'iden_notif' => 'boolean',
    ];

    public function hasIdetificationReques()
    {
        $idUser = $this->idUser;
        $identificationRequest = DB::table('identificationuserrequest')
            ->where('idUser', $idUser)
            ->where('status', StatusRequst::EnCours)
            ->first();
        if ($identificationRequest) {
            return 1;
        } else {
            return 0;
        }
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
