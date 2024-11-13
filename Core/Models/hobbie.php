<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class hobbie extends Model
{
    protected $table = 'interests';

    public function selected()
    {
        $idUser = auth()->user()->idUser;
        $usermetta_info = DB::table('metta_users')->where('idUser', $idUser)->first();
        if (in_array($this->id, json_decode(stripslashes($usermetta_info->interests)))) {
            return 1;
        } else {
            return 0;
        }
    }

}
