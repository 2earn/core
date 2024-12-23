<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class hobbie extends Model
{
    protected $table = 'interests';

    public function selected()
    {
        $usermetta_info = DB::table('metta_users')->where('idUser', auth()->user()->idUser)->first();
        return (in_array($this->id, json_decode(stripslashes($usermetta_info->interests)))) ? true : false;
    }

}
