<?php

namespace App\Http\Controllers;

use App\Models\BusinessSector;
use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;

class PlatformController extends Controller
{
    const DATE_FORMAT = 'd/m/Y H:i:s';

    public function index()
    {
        return datatables(Platform::all())
            ->addColumn('type', function ($platform) {
                return Lang::get(PlatformType::from($platform->type)->name);
            })
            ->addColumn('image', function ($platform) {
                return view('parts.datatable.platform-image', ['platform' => $platform]);
            })
            ->addColumn('action', function ($platform) {
                return view('parts.datatable.platform-action', ['platform' => $platform]);
            })
            ->addColumn('created_at', function ($platform) {
                return $platform->created_at?->format(self::DATE_FORMAT);
            })
            ->editColumn('name', function ($platform) {
                return view('parts.datatable.platform-name', ['platform' => $platform]);
            })
            ->addColumn('business_sector_id', function ($platform) {
                $businessSector = BusinessSector::find($platform->business_sector_id);
                return view('parts.datatable.platform-bussines-sector', ['businessSector' => $businessSector]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
