<?php

namespace App\DAL;

use Core\Interfaces\ILanguageRepository;
use Core\Models\language;
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
        return DB::select(getSqlFromPath('get_all_language2'));
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
