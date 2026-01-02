<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Core\Models\metta_user;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserFormContent extends Component
{
    use WithFileUploads;
    use earnTrait;
    use earnLog;

    protected UserService $userService;

    public $nbrChild = 9;
    public $photoFront;
    public $noteReject;
    public $photoBack;
    public $user;
    public $usermetta_info;
    public $numberActif;
    public $countryUser;
    public $states;
    public $paramIdUser;
    public $imageProfil;
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
    public $isPublic;
    public $newMail;

    protected $listeners = [
        'saveUser' => 'saveUser',
        'sendVerificationMail' => 'sendVerificationMail',
    ];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount(settingsManager $settingManager,Request $request)
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


        $this->paramIdUser = $request->input('paramIdUser');
        if ($this->paramIdUser == null) $this->paramIdUser = "";

        if ($this->paramIdUser == null || $this->paramIdUser == "")
            $userAuth = $settingManager->getAuthUser();
        else {
            $this->noteReject = Lang::get('Note_rejected');
            $userAuth = $settingManager->getAuthUserById($this->paramIdUser);
        }

        $this->dispalyedUserCred = getUserDisplayedName($userAuth->idUser);

        if (!$userAuth)
            abort(404);

        $this->numberActif = $settingManager->getidCountryForSms($userAuth->id)->fullNumber;

        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());
        if (is_null($usermetta_info->get('childrenCount'))) {
            $usermetta_info->put('childrenCount', 0);
        }

        $user = $this->userService->findByIdUser($userAuth->idUser);
        $this->countryUser = Lang::get($settingManager->getCountrieById($user->idCountry)->name);
        $this->usermetta_info = $usermetta_info;
        $this->user = collect($user);
        $this->originalIsPublic = $user->is_public;
        $this->states = $settingManager->getStatesContrie($user->id_phone);
        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;

    }

    public function saveUser($nbrChild, settingsManager $settingsManager)
    {
        $canModify = true;
        $us = User::find($this->user['id']);
        $um = metta_user::find($this->usermetta_info['id']);

        if ($this->paramIdUser == "" && $us->hasIdentificationRequest()) {
            $canModify = false;
        }

        if (!$canModify) {
            return redirect()->route('user_form', app()->getLocale())->with('info', Lang::get('You cant update your profile when you have an identifiaction request in progress'));
        }

        if ($canModify) {
            $um->arLastName = $this->usermetta_info['arLastName'];
            $um->arFirstName = $this->usermetta_info['arFirstName'];
            $um->enLastName = $this->usermetta_info['enLastName'];
            $um->enFirstName = $this->usermetta_info['enFirstName'];
            if (!empty($this->usermetta_info['birthday'])) {
                $um->birthday = $this->usermetta_info['birthday'];
            } else {
                $um->birthday = null;
            }
            $um->adresse = $this->usermetta_info['adresse'];
            $um->nationalID = $this->usermetta_info['nationalID'];
        }

        if ($nbrChild < 0) {
            $nbrChild = 0;
        }
        if ($nbrChild > 20) {
            $nbrChild = 20;
        }

        $um->childrenCount = $nbrChild;
        if (!empty($this->usermetta_info['idState'])) {
            $um->idState = $this->usermetta_info['idState'];
        } else {
            $um->idState = null;
        }

        $um->gender = $this->usermetta_info['gender'];
        $um->personaltitle = $this->usermetta_info['personaltitle'];
        $um->idLanguage = $this->usermetta_info['idLanguage'];

        if ($this->paramIdUser != "") {
            $us->status = StatusRequest::InProgressNational->value;
        }

        $um->save();
        $um = metta_user::find($this->usermetta_info['id']);

        // Save is_public setting
        $us->is_public = $this->user['is_public'];
        $us->save();
        $us = User::find($this->user['id']);

        try {
            if (!is_null($this->imageProfil)) {
                User::saveProfileImage($us->idUser, $this->imageProfil);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('user_form', app()->getLocale())->with('danger', Lang::get($exception->getMessage()));
        }

        if ($this->paramIdUser == "")
            return redirect()->route('user_form', app()->getLocale())->with('success', Lang::get('Edit_profil_succes'));
        else {
            $settingsManager->validateIdentity($us->idUser);
            return redirect()->route('requests_identification', app()->getLocale());
        }
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

    public function sendVerificationMail($mail, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        if (!isValidEmailAdressFormat($mail)) {
            session()->flash('danger', Lang::get('Not valid Email Format'));
            return;
        }
        if ($userAuth->email == $mail) {
            session()->flash('danger', Lang::get('Same email Address'));
            return;
        }
        $userExisteMail = $settingsManager->getConditionalUser('email', $mail);
        if ($userExisteMail && $userExisteMail->idUser != $userAuth->idUser) {
            session()->flash('danger', Lang::get('mail_used'));
            return;
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

    public function render()
    {

        return view('livewire.user-form-content')->extends('layouts.master')->section('content');
    }
}

