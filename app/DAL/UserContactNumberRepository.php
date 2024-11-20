<?php

namespace App\DAL;

use Core\Interfaces\IUserContactNumberRepository;
use Illuminate\Support\Facades\DB;

class UserContactNumberRepository implements IUserContactNumberRepository
{

    public function getActifNumber($idUser)
    {
        return collect(DB::select(getSqlFromPath('get_actif_number'), [$idUser]))->first();
    }

    public function getIDNumber($idUser)
    {
        return collect(DB::select(getSqlFromPath('get_id_number'), [$idUser]))->first();
    }
}
