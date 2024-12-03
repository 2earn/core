<?php

namespace App\DAL;

use Core\Interfaces\ICountriesRepository;
use Core\Models\countrie;
use Illuminate\Support\Facades\DB;

class  CountriesRepository implements ICountriesRepository
{

    public function getAllCountries()
    {
        return DB::table('countries')->get();
    }

    public function getCountrieById($id)
    {
        return countrie::find($id);
    }

    public function getStates($phoneCode)
    {
        return DB::select(getSqlFromPath('get_states'), [$phoneCode]);
    }

    public function getCountryByIso($iso)
    {
        return Countrie::where('apha2', $iso)->get();
    }
}
