<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Services\Targeting\Targeting;

class TargetController extends Controller
{
    public function getTargetData($idTarget)
    {
        return datatables(Targeting::getTargetQuery(Target::find($idTarget), true))->make(true);
    }
}
