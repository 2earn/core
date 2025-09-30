<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RepresentativesController extends Controller
{

    public function index()
    {
        $representatives = DB::table('representatives')->get();
        return datatables($representatives)
            ->make(true);
    }


}
