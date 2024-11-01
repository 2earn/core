<?php

namespace Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Platform extends Model
{
    protected $fillable = ['name', 'description', 'enabled', 'type', 'link','image_link','administrative_manager_id','financial_manager_id'];
    public $timestamps = true;


    public function administrativeManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function financialManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function selected($idUser)
    {
        $platforms = DB::table('user_plateforme')->where('user_id', $idUser)->get();
        if (is_null($platforms)) {
            return false;
        }
        return $platforms->where("plateforme_id", $this->id)->first() ? true : false;
    }
}
