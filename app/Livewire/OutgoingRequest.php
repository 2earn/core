<?php

namespace App\Livewire;

use App\Services\FinancialRequest\FinancialRequestService;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class OutgoingRequest extends Component
{
    public $showCanceled;
    public $requestToMee;
    public $filter;


    protected $listeners = [
        'ShowCanceled' => 'ShowCanceled',
        'AcceptRequest' => 'AcceptRequest',
        'DeleteRequest' => 'DeleteRequest',
    ];

    public function mount($filter, Request $request)
    {
        if ($request->get('ShowCancel') != null) {
            $this->showCanceled = $request->get('ShowCancel');
        } else {
            $this->showCanceled = 0;
        }
        $this->filter = $filter;
    }

    public function DeleteRequest($num, settingsManager $settingsManager, FinancialRequestService $financialRequestService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $financialRequest = $financialRequestService->getByNumeroReq($num);

        if (!$financialRequest || $financialRequest->status != 0) {
            return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4])->with('danger', Lang::get('Invalid financial request'));
        }

        $financialRequestService->cancelFinancialRequest($num, $userAuth->idUser);

        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4])->with('success', Lang::get('Delete request accepted'));
    }


    public function ShowCanceled($val)
    {
        $this->showCanceled = $val;
        $this->fromTab = 'fromRequestOut';
        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 4, 'ShowCancel' => $val]);
    }

    public function AcceptRequest($numeroRequste, FinancialRequestService $financialRequestService)
    {
        $financialRequest = $financialRequestService->getByNumeroReq($numeroRequste);

        if (!$financialRequest) return;
        if ($financialRequest->status != 0) return;

        return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $numeroRequste]);
    }

    public function render(settingsManager $settingsManager, FinancialRequestService $financialRequestService)
    {
        $userAuth = $settingsManager->getAuthUser();

        $showCanceled = $this->showCanceled == '1';
        $requestFromMee = $financialRequestService->getRequestsFromUser($userAuth->idUser, $showCanceled);

        $params = [
            'requestToMee' => $financialRequestService->getRequestsToUser($userAuth->idUser),
            'requestFromMee' => $requestFromMee,
            'requestInOpen' => $financialRequestService->countRequestsInOpen($userAuth->idUser),
            'requestOutAccepted' => $financialRequestService->countRequestsOutAccepted($userAuth->idUser),
            'requestOutRefused' => $financialRequestService->countRequestsOutRefused($userAuth->idUser)
        ];

        return view('livewire.outgoing-request', $params);
    }
}
