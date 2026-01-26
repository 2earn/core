<?php

namespace App\Livewire;

use App\Services\FinancialRequest\FinancialRequestService;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class IncomingRequest extends Component
{

    public $showCanceled;
    public $filter;

    protected $listeners = [
        'RejectRequest' => 'RejectRequest',
        'AcceptRequest' => 'AcceptRequest',
    ];

    public function mount($filter, Request $request)
    {
        $this->filter = $filter;
    }

    public function RejectRequest($numeroRequste, settingsManager $settingsManager, FinancialRequestService $financialRequestService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);

        // Validate and reject request using service
        $result = $financialRequestService->validateAndRejectRequest($numeroRequste, $userAuth->idUser);

        if (!$result['success']) {
            return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5])
                ->with('danger', Lang::get($result['message']));
        }

        return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5])
            ->with('success', Lang::get($result['message']));
    }

    public function AcceptRequest($numeroRequste, FinancialRequestService $financialRequestService)
    {
        // Validate request using service
        $result = $financialRequestService->validateRequestForAcceptance($numeroRequste);

        if (!$result['success']) {
            return redirect()->route('financial_transaction', ['locale' => app()->getLocale(), 'filter' => 5])
                ->with('danger', Lang::get($result['message']));
        }

        return redirect()->route('accept_financial_request', ['locale' => app()->getLocale(), 'numeroReq' => $numeroRequste]);
    }

    public function render(settingsManager $settingsManager, FinancialRequestService $financialRequestService)
    {
        if ($this->showCanceled == null || $this->showCanceled == "") {
            $this->showCanceled = 0;
        }
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

        return view('livewire.incoming-request', $params);
    }
}
