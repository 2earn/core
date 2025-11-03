<?php

namespace App\Http\Controllers;

use Core\Models\countrie;
use Illuminate\Http\Request;

class CountriesController extends Controller
{

    public function index()
    {
        $query = countrie::all('id', 'name', 'phonecode', 'langage');
        return datatables($query)
            ->addColumn('action', function ($country) {
                return view('parts.datatable.countries-action', ['country' => $country]);
            })
            ->make(true);
    }


}
