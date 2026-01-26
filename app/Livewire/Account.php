<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\MettaUsersService;
use App\Services\UserContactService;
use App\Services\UserService;
use Carbon\Carbon;
use App\Models\identificationuserrequest;
use App\Models\MettaUser;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Account extends Component
{
    use WithFileUploads;
    use earnTrait;
    use earnLog;
    protected UserService $userService;
    protected MettaUsersService $mettaUsersService;
    protected UserContactService $userContactService;
    public $nbrChild = 9;
    public $photoFront;
    public $noteReject;
    public $photoBack;
    public $user;
    public $newMail;
    public $usermetta_info;
    public $numberActif;
    public $countryUser;
    public $states;
    public $paramIdUser;
    public $imageProfil;
    public $PercentComplete = 0;
    public $errors_array;
    public $disabled;
    public $dispalyedUserCred;
    public $userProfileImage;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;
    public $personaltitles;
    public $genders;
    public $languages;
    public $activeTab = 'personalDetails';
    public $originalIsPublic;

    protected $listeners = [
        'sendVerificationMail' => 'sendVerificationMail',
        'saveVerifiedMail' => 'saveVerifiedMail',
        'SaveChangeEdit' => 'SaveChangeEdit',
        'sendIdentificationRequest' => 'sendIdentificationRequest',
        'saveUser' => 'saveUser',
        'deleteContact' => 'deleteContact',
        'EmailCheckUser' => 'EmailCheckUser',
        'checkUserEmail' => 'checkUserEmail',
        'cancelProcess' => 'cancelProcess',
        'saveProfileSettings' => 'saveProfileSettings',
    ];

    public function boot(UserService $userService, MettaUsersService $mettaUsersService, UserContactService $userContactService)
    {
        $this->userService = $userService;
        $this->mettaUsersService = $mettaUsersService;
        $this->userContactService = $userContactService;
    }

    public function mount(settingsManager $settingManager)
    {
        $theId = auth()->user()->idUser;

        if ($this->paramIdUser != null && $this->paramIdUser != "") {
            $userAuth = $settingManager->getAuthUserById($this->paramIdUser);
            $theId = $userAuth->idUser;
        }
        $this->userProfileImage = User::getUserProfileImage($theId);
        $this->userNationalFrontImage = User::getNationalFrontImage($theId);
        $this->userNationalBackImage = User::getNationalBackImage($theId);
        $this->userInternationalImage = User::getInternational($theId);

        $this->personaltitles = DB::table('personal_titles')->get();
        $this->genders = DB::table('genders')->get();

        $this->languages = DB::table('languages')->get();
    }


    public function SaveChangeEdit()
    {
        // Prepare data for update
        $data = [
            'enLastName' => $this->usermetta_info['enLastName'],
            'enFirstName' => $this->usermetta_info['enFirstName'],
            'birthday' => $this->usermetta_info['birthday'],
            'nationalID' => $this->usermetta_info['nationalID']
        ];

        // Delegate to service
        $result = $this->mettaUsersService->updateProfileWithImages(
            $this->usermetta_info['id'],
            $data,
            $this->photoFront,
            $this->photoBack
        );

        // Handle the result
        if (!$result['success']) {
            return redirect()->route('account', app()->getLocale())
                ->with($result['type'], Lang::get($result['message']));
        }

        return redirect()->route('account', app()->getLocale())
            ->with($result['type'], Lang::get($result['message']));
    }

    public function saveProfileSettings()
    {
        $result = $this->userService->saveProfileSettings(
            $this->user['id'],
            $this->user['is_public'],
            $this->imageProfil
        );

        if ($result['success']) {
            $this->userProfileImage = $result['userProfileImage'];
            $this->originalIsPublic = $this->user['is_public'];
            $this->imageProfil = null;
            session()->flash('success', Lang::get($result['message']));
        } else {
            session()->flash('danger', Lang::get($result['message']));
        }
    }


    public function CalculPercenteComplete()
    {
        $result = $this->mettaUsersService->calculateProfileCompleteness(
            $this->usermetta_info,
            $this->user,
            $this->usermetta_info['idUser']
        );

        $this->errors_array = $result['errors'];
        $this->PercentComplete = $result['percentComplete'];
    }


    public function deleteContact($id)
    {
        try {
            $this->userContactService->deleteContact($id);
            return redirect()->route('account', app()->getLocale())
                ->with('success', Lang::get('Contact deleted successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            return redirect()->route('account', app()->getLocale())
                ->with('danger', Lang::get($e->getMessage()));
        }
    }

    public function saveUser($nbrChild, settingsManager $settingsManager)
    {
        $result = $this->userService->saveUserProfile(
            $this->user['id'],
            $this->usermetta_info['id'],
            $this->usermetta_info->toArray(),
            $nbrChild,
            $this->user['is_public'],
            $this->paramIdUser,
            $this->imageProfil
        );

        if (!$result['success']) {
            $flashType = $result['flashType'] ?? 'danger';
            return redirect()->route($result['redirectRoute'], app()->getLocale())
                ->with($flashType, Lang::get($result['message']));
        }

        // Handle identity validation if needed
        if ($result['shouldValidateIdentity']) {
            $settingsManager->validateIdentity($result['user']->idUser);
        }

        return redirect()->route($result['redirectRoute'], app()->getLocale())
            ->with('success', Lang::get($result['message']));
    }


    public function sendVerificationMail($mail, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        if (!isValidEmailAdressFormat($mail)) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Not valid Email Format'));
        }
        if ($userAuth->email == $mail) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Same email Address'));
        }
        $userExisteMail = $settingsManager->getConditionalUser('email', $mail);
        if ($userExisteMail && $userExisteMail->idUser != $userAuth->idUser) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('mail_used'));
        }
        $opt = $this->randomNewCodeOpt();
        $us = User::find($this->user['id']);
        $us->OptActivation = $opt;
        $us->OptActivation_at = Carbon::now();
        $us->save();
        $numberActif = $settingsManager->getNumberCOntactActif($userAuth->idUser)->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::VerifMail, ['msg' => $opt, 'type' => TypeNotificationEnum::SMS]);
        $this->newMail = $mail;
        $this->dispatch('confirmOPTVerifMail', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'numberActif' => $numberActif]);
    }

    public function cancelProcess($message)
    {
        return redirect()->route('account', app()->getLocale())->with('warning', Lang::get($message));
    }

    public function checkUserEmail($codeOpt = null, settingsManager $settingsManager)
    {
        $us = User::find($this->user['id']);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }
        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', auth()->user()->id)->update(['OptActivation' => $check_exchange]);
        $settingsManager->NotifyUser(auth()->user()->id, TypeEventNotificationEnum::NewContactNumber, ['canSendMail' => 1, 'msg' => $check_exchange, 'toMail' => $this->newMail, 'emailTitle' => "2Earn.cash"]);
        $this->dispatch('EmailCheckUser', ['emailValidation' => true, 'title' => trans('Opt code from your email'), 'html' => trans('We sent an opt code to your email') . ' : ' . $this->newMail . ' <br> ' . trans('Please fill it')]);
    }

    public function saveVerifiedMail($codeOpt = null)
    {
        $us = User::find($this->user['id']);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Change user email failed - Code OPT'));
        }
        $us->email_verified = 1;
        $us->email = $this->newMail;
        $us->email_verified_at = Carbon::now();
        $us->save();
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('User email change completed successfully'));
    }

    public function approuve($idUser, settingsManager $settingsManager)
    {
        $user = User::find($idUser);
        if ($user) {
            $settingsManager->validateIdentity($user->idUser);
            return redirect()->route('requests_identification', app()->getLocale())->with('success', Lang::get('User identification request approuved') . ' : ' . $user->email);
        }
    }

    public function reject($idUser, settingsManager $settingsManager)
    {
        $user = User::find($idUser);
        if ($user) {
            $settingsManager->rejectIdentity($user->idUser, $this->noteReject);
            return redirect()->route('requests_identification', app()->getLocale())->with('success', Lang::get('User identification request rejected') . ' : ' . $user->email);
        }
    }

    public
    function sendIdentificationRequest(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $hasRequest = $userAuth->hasIdentificationRequest();
        if ($hasRequest) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Identification request exist'));
        } else {
            identificationuserrequest::create(['idUser' => $userAuth->idUser, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'response' => 0, 'note' => '', 'status' => 1]);
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification send request success'));
        }
    }


    public function render(settingsManager $settingsManager, Request $request)
    {
        $this->paramIdUser = $request->input('paramIdUser');
        if ($this->paramIdUser == null) $this->paramIdUser = "";
        if ($this->paramIdUser == null || $this->paramIdUser == "")
            $userAuth = $settingsManager->getAuthUser();
        else {
            $this->noteReject = Lang::get('Note_rejected');
            $userAuth = $settingsManager->getAuthUserById($this->paramIdUser);
        }
        $this->dispalyedUserCred = getUserDisplayedName($userAuth->idUser);

        if (!$userAuth)
            dd('not found page');
        $this->numberActif = $settingsManager->getidCountryForSms($userAuth->id)->fullNumber;

        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());
        if (is_null($usermetta_info->get('childrenCount'))) {
            $usermetta_info->put('childrenCount', 0);
        }
        $user = $this->userService->findByIdUser($userAuth->idUser);
        $this->countryUser = Lang::get($settingsManager->getCountrieById($user->idCountry)->name);
        $this->usermetta_info = $usermetta_info;
        $this->user = collect($user);
        $this->originalIsPublic = $user->is_public;
        $this->states = $settingsManager->getStatesContrie($user->id_phone);
        $this->CalculPercenteComplete();
        $hasRequest = $userAuth->hasIdentificationRequest();
        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;

        $justExpired = $lessThanSixMonths = false;
        if (!is_null(auth()->user()->expiryDate)) {
            $daysNumber = getDiffOnDays(auth()->user()->expiryDate);
            $lessThanSixMonths = $daysNumber < 180 ? true : false;
            $justExpired = $daysNumber < 1 ? true : false;
        }

        return view('livewire.account', [
            'hasRequest' => $hasRequest,
            'errors_array' => $this->errors_array,
            'justExpired' => $justExpired,
            'lessThanSixMonths' => $lessThanSixMonths
        ])->extends('layouts.master')->section('content');
    }
}
