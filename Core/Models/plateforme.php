<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class plateforme extends Model
{
    protected $table = 'plateformes';

    public function selected($idUser)
    {
        $platforms = DB::table('user_plateforme')->where('user_id', $idUser)->get();
        if($platforms == null)return 0 ;
        $existe = $platforms->where("plateforme_id", $this->id);
//            dd($existe->count()>0);
        if ($existe->count()>0)
            return 1;
        else
            return 0;
    }
}
