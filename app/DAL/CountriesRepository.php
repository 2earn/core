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
//            ->where('id','=','1')
//            ->paginate(50000);
    }

    public function getCountrieById($id)
    {
        return countrie::find($id);
    }

    public function getStates($phoneCode)
    {
        $states = DB::select('select * from states where country_id in (select id from countries where phonecode = ?)',[$phoneCode]);
        return $states ;
    }

    public function getCountryByIso($iso)
    {
        $country = DB::select('select * from countries where apha2 = ?',[$iso]) ;
        return $country ;
    }
}
