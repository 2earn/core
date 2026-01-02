<?php

namespace App\Http\Controllers;

use App\Services\CountriesService;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function __construct(
        private CountriesService $countriesService
    )
    {
    }

    public function index()
    {
        $query = $this->countriesService->getForDatatable(['id', 'name', 'phonecode', 'langage']);
        return datatables($query)
            ->addColumn('action', function ($country) {
                return view('parts.datatable.countries-action', ['country' => $country]);
            })
            ->make(true);
    }


}
