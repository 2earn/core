<?php
namespace App\DAL;
use Core\Interfaces\IUserContactNumberRepository;
use Illuminate\Support\Facades\DB;

class UserContactNumberRepository implements IUserContactNumberRepository{

    public function getActifNumber($idUser)
    {
     return  collect(DB::select('select * from usercontactnumber where idUser = ? and active = 1',[$idUser]))->first();
    }
    public function getIDNumber($idUser)
    {
        return  collect(DB::select('select * from usercontactnumber where idUser = ? and isID = 1',[$idUser]))->first();
    }
}
