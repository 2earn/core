<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Core\Enum\StatusRequst;
use Core\Models\identificationuserrequest;
use Core\Models\metta_user;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithFileUploads;


class IdentificationCheck extends Component
{

    use WithFileUploads;

    const MAX_PHOTO_ALLAWED_SIZE = 2048000;

    public $photoFront = 0;
    public $backback;
    public $photoBack = 0;
    public $photoInternational = 0;
    public $notify = true;
    public $usermetta_info2;
    public $photo;
    public $userF;
    public $internationalCard;
    public $messageVerif = "";

    public $listeners = [
        'sendIndentificationRequest' => 'sendIndentificationRequest'
    ];


    public function mount()
    {
        $this->internationalCard = !is_null(auth()->user()->internationalID) && !is_null(auth()->user()->expiryDate) ? true : false;
    }

    public function sendIndentificationRequest(settingsManager $settingsManager)
    {
        $this->messageVerif = Lang::get('Sending identification request in progress');
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $hasRequest = $userAuth->hasIdentificationRequest();
        if ($hasRequest) {
            $this->messageVerif = Lang::get('identification_exist');;
            return;
        }

        $updatedUserParams = ['email' => $this->userF['email']];
        if ($this->internationalCard) {
            $updatedUserParams = array_merge($updatedUserParams, [
                'internationalID' => $this->userF['internationalID'],
                'expiryDate' => date('Y-m-d', strtotime($this->userF['expiryDate'])),
            ]);
        }
        User::where('idUser', $userAuth->idUser)->update($updatedUserParams);
        $updatedMetaUserParams = [
            'enFirstName' => $this->usermetta_info2['enFirstName'],
            'enLastName' => $this->usermetta_info2['enLastName'],
            'birthday' => $this->usermetta_info2['birthday'],
            'nationalID' => $this->usermetta_info2['nationalID'],
        ];
        metta_user::where('idUser', $userAuth->idUser)->update($updatedMetaUserParams);
        $photoFrontValidated = $userAuth->hasFrontImage();
        $photoBackValidated = $userAuth->hasBackImage();
        $photoInternationalValidated = $userAuth->hasInternationalIdentity();

        if (!is_null($this->photoFront) && gettype($this->photoFront) == "object") {
            if ($this->photoFront->extension() == 'png') {
                if ($this->photoFront->getSize() < self::MAX_PHOTO_ALLAWED_SIZE) {
                    $this->photoFront->storeAs('profiles', 'front-id-image' . $userAuth->idUser . '.png', 'public2');
                    $photoFrontValidated = true;
                } else {
                    $photoFrontValidated = false;
                    $this->messageVerif = Lang::get('Identification request missing information');;
                    $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Photo front big size'),]);
                    return;
                }
            } else {
                $photoFrontValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Photo front wrong type'),]);
                return;
            }
        }

        if (!is_null($this->photoBack) && gettype($this->photoBack) == "object") {
            if ($this->photoBack->extension() == 'png') {
                if ($this->photoBack->getSize() < self::MAX_PHOTO_ALLAWED_SIZE) {
                    $this->photoBack->storeAs('profiles', 'back-id-image' . $userAuth->idUser . '.png', 'public2');
                    $photoBackValidated = true;
                } else {
                    $photoBackValidated = false;
                    $this->messageVerif = Lang::get('Identification request missing information');;
                    $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Photo back big size'),]);
                    return;
                }
            } else {
                $photoFrontValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Photo back wrong type'),]);
                return;
            }
        }

        if ($this->internationalCard && !is_null($this->photoInternational) && gettype($this->photoInternational) == "object") {
            if ($this->photoInternational->extension() == 'png') {
                if ($this->photoInternational->getSize() < self::MAX_PHOTO_ALLAWED_SIZE) {
                    $this->photoInternational->storeAs('profiles', 'international-id-image' . $userAuth->idUser . '.png', 'public2');
                    $photoInternationalValidated = true;
                } else {
                    $photoInternationalValidated = false;
                    $this->messageVerif = Lang::get('Identification request missing information');;
                    $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('International Identity big size')]);
                    return;
                }
            } else {
                $photoInternationalValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('International Identity wrong type')]);
                return;
            }
        }

        if ($photoBackValidated && $photoFrontValidated && (!$this->internationalCard or ($this->internationalCard && $photoInternationalValidated))) {
            $this->sendIdentificationRequest($settingsManager);
            User::where('idUser', $userAuth->idUser)->update(['status' => 1, 'asked_at' => date('Y-m-d H:i:s'), 'iden_notif' => $this->notify]);
            $this->messageVerif = Lang::get('demande_creer');
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification_send_succes'));
        } else {
            $this->messageVerif = Lang::get('Identification request missing information');
            $this->dispatchBrowserEvent('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request missing information'), 'text' => Lang::get('Identification request missing information'),]);
        }
    }

    public function save(settingsManager $settingsManager)
    {
        $redirect = false;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');
        User::where('idUser', $userAuth->idUser)->update(['status' => -1, 'asked_at' => date('Y-m-d H:i:s'), 'iden_notif' => $this->notify]);
        return redirect()->route('account', app()->getLocale())->with('SuccesUpdateIdentification', Lang::get('Identification_send_succes'));
    }

    public function render(settingsManager $settingsManager)
    {
        $noteRequset = "";
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');
        $user = DB::table('users')->where('idUser', $userAuth->idUser)->first();
        if (!$user) abort(404);
        $this->userF = collect($user);
        $errors_array = array();

        $usermetta_info = DB::table('metta_users')->where('idUser', $userAuth->idUser)->first();

        $this->usermetta_info2 = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());

        if ($usermetta_info->enFirstName == null) {
            array_push($errors_array, getProfileMsgErreur('enFirstName'));
        }
        if ($usermetta_info->enLastName == null) {
            array_push($errors_array,getProfileMsgErreur('enLastName'));
        }
        if ($usermetta_info->birthday == null) {
            array_push($errors_array, getProfileMsgErreur('birthday'));
        }
        if ($usermetta_info->nationalID == null) {
            array_push($errors_array, getProfileMsgErreur('nationalID'));
        }
        if (!isset($user->email) && trim($user->email) == "") {
            array_push($errors_array, getProfileMsgErreur('email'));
        }

        $this->notify = $userAuth->iden_notif;
        $hasRequest = $userAuth->hasIdentificationRequest();
        $hasFrontImage = $userAuth->hasFrontImage();
        $hasBackImage = $userAuth->hasBackImage();

        $requestIdentification = identificationuserrequest::where('idUser', $userAuth->idUser)
            ->where('status', StatusRequst::Rejected->value)
            ->latest('responseDate')
            ->first();
        if ($requestIdentification != null) {
            $noteRequset = $requestIdentification->note;
        }
        return view('livewire.identification-check',
            compact('user', 'usermetta_info', 'errors_array', 'userAuth', 'hasRequest', 'hasFrontImage', 'hasBackImage', 'noteRequset'))
            ->extends('layouts.master')->section('content');
    }

    public function sendIdentificationRequest(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $hasRequest = $userAuth->hasIdentificationRequest();
        if (!$hasRequest) {
            $sensIdentification = identificationuserrequest::create(
                ['idUser' => $userAuth->idUser, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'response' => 0, 'note' => '', 'status' => 1]
            );
        }
        $this->dispatchBrowserEvent('existIdentificationRequest', ['type' => 'warning', 'title' => "Opt", 'text' => '',]);
    }
}
