<?php

namespace Core\Models;

use App\Models\Deal;
use App\Models\ProductDealHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Platform extends Model
{
    protected $fillable = ['name', 'description', 'enabled', 'type', 'link', 'image_link', 'administrative_manager_id', 'financial_manager_id'];
    public $timestamps = true;


    public function administrativeManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function financialManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function productDealHistory(): HasMany
    {
        return $this->hasMany(ProductDealHistory::class);
    }

    public function deals()
    {
        return $this->belongsTo(Deal::class);
    }

    public function selected($idUser)
    {
        $platforms = DB::table('user_plateforme')->where('user_id', $idUser)->get();
        if (is_null($platforms)) {
            return false;
        }
        return $platforms->where("plateforme_id", $this->id)->first() ? true : false;
    }


    public static function canCheckDeals($id)
    {

        if (strtoupper(auth()?->user()?->getRoleNames()->first()) == \App\Models\Survey::SUPER_ADMIN_ROLE_NAME) {
            return true;
        }

        return Platform::where(function ($query) use ($id) {
            $query->where('administrative_manager_id', '=', $id)
                ->orWhere('financial_manager_id', '=', $id);
        })->exists();
    }

}
