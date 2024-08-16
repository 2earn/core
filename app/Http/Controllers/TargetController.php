<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Services\Targeting\Targeting;

class TargetController extends Controller
{
    public function getTargetData($idTarget)
    {
        $target = Target::find($idTarget);
        $data = Targeting::getTargetQuery($target, true);
        return datatables($data)->make(true);
    }
}
