<?php

namespace App\DAL;

use Core\Interfaces\ILanguageRepository;
use Core\Models\countrie;
use Core\Models\history;
use Core\Models\language;
use Core\Models\user_balance;
use Core\Models\user_earn;
use Illuminate\Support\Facades\DB;

class  LanguageRepository implements ILanguageRepository
{
    public function getAllLanguage()
    {
        return DB::table('languages')
            ->get();
    }

    public function getAllLanguage2()
    {
        return DB::select("SELECT * FROM demo.user_balances ub inner join metta_users mu on ub.idUser = mu.idUser ");
    }


    public function addLanguage(language $language)
    {
        language::create(
            [
            ]
        );
    }

    public function getLanguageById($id)
    {
        return language::find($id);
    }

    public function getLanguageByPrefix($prefix)
    {
        return language::firstOrFail()->where('PrefixLanguage', $prefix)->get()->first();
    }
}
