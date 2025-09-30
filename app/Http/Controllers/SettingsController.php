<?php

namespace App\Http\Controllers;

use Core\Models\Setting;
use Illuminate\Support\Facades\Lang;

class SettingsController extends Controller
{
    const DATE_FORMAT = 'd/m/Y H:i:s';
    const SEPARATOR = ' : ';

    public function index()
    {
        return datatables(Setting::all())
            ->addColumn('action', function ($settings) {
                return view('parts.datatable.settings-action', ['settings' => $settings]);
            })
            ->addColumn('value', function ($settings) {
                if (!is_null($settings->IntegerValue)) return Lang::get('Integer') . self::SEPARATOR . $settings->IntegerValue;
                if (!is_null($settings->StringValue)) return Lang::get('String') . self::SEPARATOR . $settings->StringValue;
                if (!is_null($settings->DecimalValue)) return Lang::get('Decimal') . self::SEPARATOR . $settings->DecimalValue;
                return 0;
            })
            ->editColumn('Automatically_calculated', function ($settings) {
                return view('parts.datatable.settings-auto', ['settings' => $settings]);
            })
            ->rawColumns(['value', 'action'])
            ->toJson();
    }

}
