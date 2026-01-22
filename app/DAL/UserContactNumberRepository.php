<?php

namespace App\DAL;

use App\Interfaces\IUserContactNumberRepository;
use App\Models\UserContactNumber;
use Illuminate\Support\Facades\DB;

class UserContactNumberRepository implements IUserContactNumberRepository
{

    public function getActifNumber($idUser)
    {
        return  UserContactNumber::where("idUser", $idUser)->where("active", 1)->first();
    }

    public function getIDNumber($idUser)
    {
        return  UserContactNumber::where("idUser", $idUser)->where("isID", 1)->first();
    }
}
