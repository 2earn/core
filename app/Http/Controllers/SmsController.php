<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{

    public function index()
    {
        return view('livewire.sms-index');
    }

    public function getSmsData(Request $request)
    {
        $query = Sms::query()
            ->select(
                'sms.id',
                'sms.message',
                'sms.destination_number',
                'sms.source_number',
                'sms.created_at',
                'sms.updated_at',
                'sms.created_by',
                'sms.updated_by',
                DB::raw('(SELECT CONCAT(IFNULL(mu.enFirstName, ""), " ", IFNULL(mu.enLastName, "")) FROM users u LEFT JOIN metta_users mu ON u.idUser = mu.idUser WHERE u.id = sms.created_by LIMIT 1) as user_name')
            );

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('sms.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sms.created_at', '<=', $request->date_to);
        }

        if ($request->filled('destination_number')) {
            $query->where('sms.destination_number', 'like', '%' . $request->destination_number . '%');
        }

        if ($request->filled('message')) {
            $query->where('sms.message', 'like', '%' . $request->message . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('sms.created_by', $request->user_id);
        }

        return DataTables::of($query)
            ->addColumn('user_info', function ($sms) {
                if ($sms->user_name) {
                    return '<div class="d-flex align-items-center">' .
                           '<div class="flex-grow-1">' .
                           '<h5 class="fs-13 mb-0">' . $sms->user_name . '</h5>' .
                           '<p class="text-muted mb-0">ID: ' . ($sms->created_by ?? 'N/A') . '</p>' .
                           '</div></div>';
                }
                return '<span class="badge bg-secondary">System</span>';
            })
            ->addColumn('message_preview', function ($sms) {
                $message = $sms->message ?? '';
                if (strlen($message) > 50) {
                    return '<span title="' . htmlspecialchars($message) . '">' .
                           htmlspecialchars(substr($message, 0, 50)) . '...</span>';
                }
                return htmlspecialchars($message);
            })
            ->addColumn('phone_info', function ($sms) {
                return '<div>' .
                       '<strong>' . ($sms->destination_number ?? 'N/A') . '</strong><br>' .
                       '<small class="text-muted">From: ' . ($sms->source_number ?? 'N/A') . '</small>' .
                       '</div>';
            })
            ->editColumn('created_at', function ($sms) {
                return '<div>' .
                       '<div>' . $sms->created_at->format('Y-m-d') . '</div>' .
                       '<small class="text-muted">' . $sms->created_at->format('H:i:s') . '</small>' .
                       '</div>';
            })
            ->rawColumns(['user_info', 'message_preview', 'phone_info', 'created_at', 'action'])
            ->make(true);
    }


}

