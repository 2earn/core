<?php
namespace Core\Models;
use Core\Enum\StatusRequst;
use Illuminate\Support\Facades\DB;

class  AuthenticatedUser  {


    protected $casts = [
        'iden_notif' => 'boolean',
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

    public function hasFrontImage(){

        if (file_exists('uploads/profiles/front-id-image' .$this->idUser . '.png'))
            return 1;
        else
            return 0;
    }
    public function hasBackImage(){
        if (file_exists('uploads/profiles/back-id-image' . $this->idUser . '.png'))
            return 1;
        else
            return 0;
    }
}
