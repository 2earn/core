<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\IdentificationUserRequestService;
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
    protected IdentificationUserRequestService $identificationRequestService;

    const MAX_PHOTO_ALLAWED_SIZE = 2048000;

    // Public properties for file uploads
    public $photoFront;
    public $photoBack;
    public $photoInternational;

    // Public properties for form data
    public $internationalCard = false;
    public $notify = true;
    public $usermetta_info2;
    public $userF;

    // Public properties for images
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;

    // Public properties for UI state
    public $messageVerif = "";
    public $disabled = false;

    public function boot(UserService $userService, IdentificationUserRequestService $identificationRequestService)
    {
        $this->userService = $userService;
        $this->identificationRequestService = $identificationRequestService;
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
        if (!$userAuth) {
            dd('not found page');
        }

        // Save identification status using service
        $result = $this->userService->saveIdentificationStatus($userAuth->idUser, $this->notify);

        if (!$result['success']) {
            return redirect()->route('account', app()->getLocale())
                ->with('danger', Lang::get($result['message']));
        }

        return redirect()->route('account', app()->getLocale())
            ->with('success', Lang::get($result['message']));
    }


    public function sendIdentificationRequest($newStatus, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();

        // Check if user already has a request
        $hasRequest = $this->identificationRequestService->hasIdentificationRequest($userAuth->idUser);

        if (!$hasRequest) {
            // Create identification request using service
            $result = $this->identificationRequestService->createIdentificationRequest(
                $userAuth->idUser,
                $newStatus
            );

            if (!$result['success']) {
                Log::error('Failed to create identification request', [
                    'idUser' => $userAuth->idUser,
                    'status' => $newStatus
                ]);
            }
        }

        $this->dispatch('existIdentificationRequest', ['type' => 'warning', 'title' => "Opt", 'text' => '',]);
    }

    /**
     * Validate metta user first name
     *
     * @param object $mettaUser
     * @param array &$errors
     * @return void
     */
    private function validateFirstName($mettaUser, array &$errors): void
    {
        if ($mettaUser->enFirstName == null) {
            $errors[] = getProfileMsgErreur('enFirstName');
        }
    }

    /**
     * Validate metta user last name
     *
     * @param object $mettaUser
     * @param array &$errors
     * @return void
     */
    private function validateLastName($mettaUser, array &$errors): void
    {
        if ($mettaUser->enLastName == null) {
            $errors[] = getProfileMsgErreur('enLastName');
        }
    }

    /**
     * Validate metta user birthday
     *
     * @param object $mettaUser
     * @param array &$errors
     * @return void
     */
    private function validateBirthday($mettaUser, array &$errors): void
    {
        if ($mettaUser->birthday == null) {
            $errors[] = getProfileMsgErreur('birthday');
        }
    }

    /**
     * Validate metta user national ID
     *
     * @param object $mettaUser
     * @param array &$errors
     * @return void
     */
    private function validateNationalID($mettaUser, array &$errors): void
    {
        if ($mettaUser->nationalID == null) {
            $errors[] = getProfileMsgErreur('nationalID');
        }
    }

    /**
     * Validate user email
     *
     * @param object $user
     * @param array &$errors
     * @return void
     */
    private function validateEmail($user, array &$errors): void
    {
        if (!isset($user->email) || trim($user->email) == "") {
            $errors[] = getProfileMsgErreur('email');
        }
    }

    /**
     * Validate all profile fields for identification
     *
     * @param object $user
     * @param object $mettaUser
     * @return array Array of error messages
     */
    private function validateProfileForIdentification($user, $mettaUser): array
    {
        $errors = [];

        $this->validateFirstName($mettaUser, $errors);
        $this->validateLastName($mettaUser, $errors);
        $this->validateBirthday($mettaUser, $errors);
        $this->validateNationalID($mettaUser, $errors);
        $this->validateEmail($user, $errors);

        return $errors;
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

        $usermetta_info = DB::table('metta_users')->where('idUser', $userAuth->idUser)->first();
        $this->usermetta_info2 = collect($usermetta_info);

        // Validate profile using separated validation methods
        $errors_array = $this->validateProfileForIdentification($user, $usermetta_info);

        $this->notify = $userAuth->iden_notif;
        $hasRequest = $userAuth->hasIdentificationRequest();


        $hasFrontImage = User::getNationalFrontImage($userAuth->idUser) != User::DEFAULT_NATIONAL_FRONT_URL;
        $hasBackImage = User::getNationalBackImage($userAuth->idUser) != User::DEFAULT_NATIONAL_BACK_URL;

        // Get latest rejected request using service
        $requestIdentification = $this->identificationRequestService->getLatestRejectedRequest(
            $userAuth->idUser,
            StatusRequest::Rejected->value
        );

        if ($requestIdentification != null) {
            $noteRequset = $requestIdentification->note;
        }

        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;

        // Make component properties available to the view
        $usermetta_info2 = $this->usermetta_info2;
        $userF = $this->userF;
        $userNationalFrontImage = $this->userNationalFrontImage;
        $userNationalBackImage = $this->userNationalBackImage;
        $userInternationalImage = $this->userInternationalImage;
        $disabled = $this->disabled;
        $internationalCard = $this->internationalCard;

        return view('livewire.identification-check',
            compact('user', 'usermetta_info', 'usermetta_info2', 'userF', 'errors_array', 'userAuth', 'hasRequest', 'hasFrontImage', 'hasBackImage', 'noteRequset', 'userNationalFrontImage', 'userNationalBackImage', 'userInternationalImage', 'disabled', 'internationalCard'))
            ->extends('layouts.master')->section('content');
    }
}
