<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    const DATE_FORMAT = 'd/m/Y H:i:s';

    public function index()
    {
        return datatables(Role::all())
            ->addColumn('action', function ($role) {
                return view('parts.datatable.role-action', ['roleId' => $role->id, 'roleName' => $role->name]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })
            ->addColumn('updated_at', function ($platform) {
                return $platform->updated_at?->format(self::DATE_FORMAT);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
