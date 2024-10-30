<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Services\Targeting\Targeting;

class TargetController extends Controller
{
    public function getTargetData($locale, $idTarget)
    {
        return datatables(Targeting::getTargetQuery(Target::find($idTarget), true))
            ->addColumn('detail', function ($query) {
                return view('parts.datatable.user-detail-link', ['id' => $query->id]);
            })
            ->rawColumns(['detail'])
            ->make(true);
    }
}
