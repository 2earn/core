<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
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

    public function getAmountsQuery()
    {
        return DB::table('amounts')
            ->select('idamounts', 'amountsname', 'amountswithholding_tax', 'amountspaymentrequest', 'amountstransfer', 'amountscash', 'amountsactive', 'amountsshortname');
    }

    public function getAmounts()
    {
        return datatables($this->getAmountsQuery())
            ->addColumn('action', function ($amounts) {
                return view('parts.datatable.amounts-action', ['amounts' => $amounts]);
            })
            ->editColumn('amountswithholding_tax', function ($amounts) {
                return view('parts.datatable.amounts-tax', ['amounts' => $amounts]);
            })
            ->editColumn('amountstransfer', function ($amounts) {
                return view('parts.datatable.amounts-transfer', ['amounts' => $amounts]);
            })
            ->editColumn('amountspaymentrequest', function ($amounts) {
                return view('parts.datatable.amounts-payment', ['amounts' => $amounts]);
            })
            ->editColumn('amountscash', function ($amounts) {
                return view('parts.datatable.amounts-cash', ['amounts' => $amounts]);
            })
            ->editColumn('amountsactive', function ($amounts) {
                return view('parts.datatable.amounts-active', ['amounts' => $amounts]);
            })
            ->escapeColumns([])
            ->make(true);
    }

}
