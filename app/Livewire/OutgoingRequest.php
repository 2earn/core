<?php

namespace App\Livewire;

use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class OutgoingRequest extends Component
{
    public $showCanceled;
    public $requestToMee;

    const DATE_FORMAT = 'Y-m-d H:i:s';

    protected $listeners = [
        'ShowCanceled' => 'ShowCanceled',
        'AcceptRequest' => 'AcceptRequest',
        'DeleteRequest' => 'DeleteRequest',
    ];

    public $filter;

    public function mount($filter, Request $request)
    {
        $this->filter = $filter;
    }

    public function DeleteRequest($num, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $financialRequest = FinancialRequest::where('numeroReq', '=', $num)->first();

        if (!$financialRequest || $financialRequest->status != 0) {
            return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4])->with('danger', Lang::get('Invalid financial request'));
        }
        FinancialRequest::where('numeroReq', '=', $num)->update(
            ['status' => 3, 'idUserAccepted' => $userAuth->idUser, 'dateAccepted' => date(self::DATE_FORMAT)]
        );
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4])->with('success', Lang::get('Delete request accepted'));
    }


    public function ShowCanceled($val)
    {
        $this->showCanceled = $val;
        $this->fromTab = 'fromRequestOut';
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4, 'ShowCancel' => $val])->with('info', 'sdf');
    }

    public function AcceptRequest($numeroRequste)
    {
        $financialRequest = FinancialRequest::where('numeroReq', '=', $numeroRequste)->first();
        if (!$financialRequest) return;
        if ($financialRequest->status != 0) return;
        return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $numeroRequste]);
    }

    public function render(settingsManager $settingsManager)
    {
        if ($this->showCanceled == null || $this->showCanceled == "") {
            $this->showCanceled = 0;
        }
        $userAuth = $settingsManager->getAuthUser();
        if ($this->showCanceled == '1') {
            $requestFromMee = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->join('users as u1', 'financial_request.idSender', '=', 'u1.idUser')
                ->with('details', 'details.User')
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'u1.name', 'u1.mobile', 'financial_request.amount', 'financial_request.status as FStatus', 'financial_request.securityCode']);
        } else {
            $requestFromMee = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', '!=', '3')
                ->join('users as u1', 'financial_request.idSender', '=', 'u1.idUser')
                ->with('details', 'details.User')
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'u1.name', 'u1.mobile', 'financial_request.amount', 'financial_request.status as FStatus', 'financial_request.securityCode']);
        }

        $params = [
            'requestToMee' => detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
                ->join('users', 'financial_request.idSender', '=', 'users.idUser')
                ->where('detail_financial_request.idUser', $userAuth->idUser)
                ->orderBy('financial_request.date', 'desc')
                ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status'])
            ,
            'requestFromMee' => $requestFromMee,
            'requestInOpen' => detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
                ->where('detail_financial_request.idUser', $userAuth->idUser)
                ->where('financial_request.Status', 0)
                ->where('detail_financial_request.vu', 0)
                ->count(),
            'requestOutAccepted' => FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', 1)
                ->where('financial_request.vu', 0)
                ->count(),
            'requestOutRefused' => FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
                ->where('financial_request.Status', 5)
                ->where('financial_request.vu', 0)
                ->count()
        ];
        return view('livewire.outgoing-request', $params);
    }
}
