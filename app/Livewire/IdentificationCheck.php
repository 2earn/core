<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use App\Models\identificationuserrequest;
use App\Models\MettaUser;
use App\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;


class IdentificationCheck extends Component
{

    use WithFileUploads;

    protected UserService $userService;

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
    public $disabled;
    public $userNationalBackImage;
    public $userNationalFrontImage;
    public $userInternationalImage;

    public $listeners = ['sendIndentificationRequest' => 'sendIndentificationRequest'];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        if (!is_null(auth()->user()->internationalID) && !is_null(auth()->user()->expiryDate)) {
            $this->internationalCard = true;
        } else {
            $this->internationalCard = false;
        }
        if (auth()->user()->status == StatusRequest::ValidNational->value) {
            $this->internationalCard == true;
        }

        $this->userNationalFrontImage = User::getNationalFrontImage(auth()->user()->idUser);
        $this->userNationalBackImage = User::getNationalBackImage(auth()->user()->idUser);
        $this->userInternationalImage = User::getInternational(auth()->user()->idUser);
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
        MettaUser::where('idUser', $userAuth->idUser)->update($updatedMetaUserParams);


        $photoFrontValidated = User::getNationalFrontImage($userAuth->idUser) != User::DEFAULT_NATIONAL_FRONT_URL;
        $photoBackValidated = User::getNationalBackImage($userAuth->idUser) != User::DEFAULT_NATIONAL_BACK_URL;
        $photoInternationalValidated = User::getInternational($userAuth->idUser) != User::DEFAULT_INTERNATIONAL_URL;

        if ($this->photoFront) {
            try {
                User::saveNationalFrontImage($userAuth->idUser, $this->photoFront);
                $photoFrontValidated = true;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                $photoFrontValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatch('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Front id image') . ' : ' . Lang::get($exception->getMessage())]);
                return;
            }
        }
        if ($this->photoBack) {
            try {
                User::saveNationalBackImage($userAuth->idUser, $this->photoBack);
                $photoBackValidated = true;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                $photoBackValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatch('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('Back id image') . ' : ' . Lang::get($exception->getMessage())]);
                return;
            }
        }

        if ($this->internationalCard && $this->photoInternational) {
            try {
                User::saveInternationalImage($userAuth->idUser, $this->photoInternational);
                $photoInternationalValidated = true;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                $photoInternationalValidated = false;
                $this->messageVerif = Lang::get('Identification request missing information');;
                $this->dispatch('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request wrong information'), 'text' => Lang::get('International id image') . ' : ' . Lang::get($exception->getMessage())]);
                return;
            }
        }

        if ($photoBackValidated && $photoFrontValidated && (!$this->internationalCard or ($this->internationalCard && $photoInternationalValidated))) {
            $user = User::where('idUser', $userAuth->idUser)->first();
            $newStatus = StatusRequest::InProgressNational->value;
            if ($this->internationalCard) {
                if ($user->status == StatusRequest::ValidNational->value || $user->status == StatusRequest::ValidInternational->value) {
                    $newStatus = StatusRequest::InProgressInternational->value;
                }
                if ($user->status == StatusRequest::OptValidated->value) {
                    $newStatus = StatusRequest::InProgressGlobal->value;
                }
            }

            $this->sendIdentificationRequest($newStatus, $settingsManager);
            User::where('idUser', $userAuth->idUser)->update(['status' => $newStatus, 'asked_at' => date(config('app.date_format')), 'iden_notif' => $this->notify]);
            $this->messageVerif = Lang::get('Create request');
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification send request success'));
        } else {
            $this->messageVerif = Lang::get('Identification request missing information');
            $this->dispatch('IdentificationRequestMissingInformation', ['type' => 'warning', 'title' => Lang::get('Identification request missing information'), 'text' => Lang::get('Identification request missing information'),]);
        }
    }

    public function save(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');
        User::where('idUser', $userAuth->idUser)->update(['status' => -1, 'asked_at' => date(config('app.date_format')), 'iden_notif' => $this->notify]);
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification send request success'));
    }


    public function sendIdentificationRequest($newStatus, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $hasRequest = $userAuth->hasIdentificationRequest();
        if (!$hasRequest) {
            identificationuserrequest::create([
                    'idUser' => $userAuth->idUser,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'response' => 0,
                    'note' => '',
                    'status' => $newStatus
                ]
            );
        }
        $this->dispatch('existIdentificationRequest', ['type' => 'warning', 'title' => "Opt", 'text' => '',]);
    }


    public function render(settingsManager $settingsManager)
    {
        $noteRequset = "";
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');
        $user = $this->userService->findByIdUser($userAuth->idUser);
        if (!$user) abort(404);
        $this->userF = collect($user);
        $errors_array = array();

        $usermetta_info = DB::table('metta_users')->where('idUser', $userAuth->idUser)->first();

        $this->usermetta_info2 = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());

        if ($usermetta_info->enFirstName == null) {
            array_push($errors_array, getProfileMsgErreur('enFirstName'));
        }
        if ($usermetta_info->enLastName == null) {
            array_push($errors_array, getProfileMsgErreur('enLastName'));
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


        $hasFrontImage = User::getNationalFrontImage($userAuth->idUser) != User::DEFAULT_NATIONAL_FRONT_URL;
        $hasBackImage = User::getNationalBackImage($userAuth->idUser) != User::DEFAULT_NATIONAL_BACK_URL;

        $requestIdentification = identificationuserrequest::where('idUser', $userAuth->idUser)
            ->where('status', StatusRequest::Rejected->value)
            ->latest('responseDate')
            ->first();
        if ($requestIdentification != null) {
            $noteRequset = $requestIdentification->note;
        }
        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;
        return view('livewire.identification-check',
            compact('user', 'usermetta_info', 'errors_array', 'userAuth', 'hasRequest', 'hasFrontImage', 'hasBackImage', 'noteRequset'))
            ->extends('layouts.master')->section('content');
    }
}
