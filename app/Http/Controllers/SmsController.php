<?php

namespace App\Http\Controllers;

use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\User;
use App\Services\sms\SmsService;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        return view('livewire.sms-index');
    }

    public function getSmsData(Request $request)
    {
        $filters = [
            'date_from' => $request->filled('date_from') ? $request->date_from : null,
            'date_to' => $request->filled('date_to') ? $request->date_to : null,
            'destination_number' => $request->filled('destination_number') ? $request->destination_number : null,
            'message' => $request->filled('message') ? $request->message : null,
            'user_id' => $request->filled('user_id') ? $request->user_id : null,
        ];

        $query = $this->smsService->getSmsDataQuery(array_filter($filters));

        return DataTables::of($query)
            ->addColumn('user_info', function ($sms) {
                return view('parts.datatable.sms-user-info', [
                    'user_name' => $sms->user_name,
                    'created_by' => $sms->created_by
                ])->render();
            })
            ->addColumn('message_preview', function ($sms) {
                return view('parts.datatable.sms-message-preview', [
                    'message' => $sms->message
                ])->render();
            })
            ->addColumn('phone_info', function ($sms) {
                return view('parts.datatable.sms-phone-info', [
                    'destination_number' => $sms->destination_number,
                    'source_number' => $sms->source_number
                ])->render();
            })
            ->editColumn('created_at', function ($sms) {
                return view('parts.datatable.sms-created-at', [
                    'created_at' => $sms->created_at
                ])->render();
            })
            ->rawColumns(['user_info', 'message_preview', 'phone_info', 'created_at', 'action'])
            ->make(true);
    }

    public function getStatistics(Request $request)
    {
        return response()->json($this->smsService->getStatistics());
    }

    public function show($id)
    {
        $sms = $this->smsService->findById($id);

        if (!$sms) {
            return response()->json(['error' => 'SMS not found'], 404);
        }

        $user = null;

        if ($sms->created_by) {
            $user = User::with('mettaUser')->find($sms->created_by);
            if ($user && $user->mettaUser) {
                $user = $user->mettaUser;
            }
        }

        return response()->json([
            'sms' => $sms,
            'user' => $user
        ]);
    }

    public function SendSMS(Request $request, settingsManager $settingsManager)
    {
        $settingsManager->NotifyUser($request->user, TypeEventNotificationEnum::none, ['msg' => $request->msg, 'type' => TypeNotificationEnum::SMS]);
    }

}

