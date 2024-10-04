<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Services\Targeting\Targeting;
use Illuminate\Support\Facades\Lang;

class TargetController extends Controller
{
    public function getTargetData($locale,$idTarget)
    {
        return datatables(Targeting::getTargetQuery(Target::find($idTarget), true))
            ->addColumn('detail', function ($query) {
                return '<a target=”_blank” href="' . route('user_details', ['locale' => app()->getLocale(), 'idUser' => $query->id]) . '">' . Lang::get('See Details') . '</a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }
}
