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

    public $photoFront=0;
    public $backback;
    public $photoBack;
    public $notify = true;
    public $usermetta_info2;
    public $photo;
    public $userF;
    public $messageVerif ="";
    public $listeners = [
        'saveimg' => 'saveimg'
    ];


    public function saveimg(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(4040);
        $hasRequest = $userAuth->hasIdetificationReques();
        if($hasRequest)
        {
            $this->messageVerif =Lang::get('identification_exist') ; ;
            return ;
        }

        User::where('idUser', $userAuth->idUser)->update([
            'email' => $this->userF['email'],
        ]);
        metta_user::where('idUser', $userAuth->idUser)->update([
            'enFirstName' => $this->usermetta_info2['enFirstName'],
            'enLastName' => $this->usermetta_info2['enLastName'],
            'birthday' => $this->usermetta_info2['birthday'],
            'nationalID' => $this->usermetta_info2['nationalID'],
        ]);

        if ($this->photoFront != null && $this->photoFront != 0)
            $p = $this->photoFront->storeAs('profiles', 'front-id-image' . $userAuth->idUser . '.png', 'public2');
        if ($this->backback != null)
            $p = $this->backback->storeAs('profiles', 'back-id-image' . $userAuth->idUser . '.png', 'public2');

        // NOTE TO DO check identification workflow
        $this->sendIdentificationRequest($settingsManager);
        User::where('idUser', $userAuth->idUser)->update(['status' => -1, 'asked_at' => date('Y-m-d H:i:s'), 'iden_notif' => $this->notify]);
        $this->messageVerif = Lang::get('demande_creer') ;
    }

    public function save(settingsManager $settingsManager)
    {
        $redirect = false;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');
//        if ($this->photoFront != null) {
//            $this->validate([
//                'photoFront' => 'image',
//            ]);
//            $lenthFront = strlen($this->photoFront->getClientOriginalName());
//            $posFront = strrpos($this->photoFront->getClientOriginalName(), '.');
//            $extFront = substr($this->photoFront->getClientOriginalName(), $posFront, $lenthFront);
//            $p = $this->photoFront->storeAs('profiles', 'front-id-image' . $userAuth->idUser . '.png', 'public2');
//            $redirect = true;
//        }
//        if ($this->photoBack != null) {
//            $this->validate([
//                'photoBack' => 'image',
//            ]);
//            $lenthBack = strlen($this->photoBack->getClientOriginalName());
//            $posBack = strrpos($this->photoBack->getClientOriginalName(), '.');
//            $extBack = substr($this->photoBack->getClientOriginalName(), $posBack, $lenthBack);
//            $p = $this->photoBack->storeAs('profiles', 'back-id-image' . $userAuth->idUser . '.png', 'public2');
//            $redirect = true;
//        }
        User::where('idUser', $userAuth->idUser)->update(['status' => -1, 'asked_at' => date('Y-m-d H:i:s'), 'iden_notif' => $this->notify]);
//        dd($this->photoBack->getMimeType());
        //  if ($redirect)
        return redirect()->route('account', app()->getLocale())->with('SuccesUpdateIdentification', Lang::get('Identification_send_succes'));
    }

    private function getMsgErreur($typeErreur)
    {
        $typeErreur = 'Identify_' . $typeErreur;
        return Lang::get($typeErreur);
    }

    public function render(settingsManager $settingsManager)
    {
        $noteRequset = "";
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth)
            dd('not found page');

//        $user = $settingsManager->getUserById($userAuth->id);
        $user = DB::table('users')->where('idUser', $userAuth->idUser)->first();
        if (!$user) abort(404);
        $this->userF = collect($user);
//        $userAuth = $userAuth;
        $errors_array = array();

        $usermetta_info = DB::table('metta_users')->where('idUser', $userAuth->idUser)->first();

        $this->usermetta_info2 = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());
        if ($usermetta_info->enFirstName == null) {
            array_push($errors_array, $this->getMsgErreur('enFirstName'));
        }
        if ($usermetta_info->enLastName == null) {
            array_push($errors_array, $this->getMsgErreur('enLastName'));
        }
        if ($usermetta_info->birthday == null) {
            array_push($errors_array, $this->getMsgErreur('birthday'));
        }
        if ($usermetta_info->nationalID == null) {
            array_push($errors_array, $this->getMsgErreur('nationalID'));
        }
//        if ($usermetta_info->email == null)
//        {
//            array_push($errors_array, $this->getMsgErreur('email'));
//        }
        $this->notify = $userAuth->iden_notif;
        $hasRequest = $userAuth->hasIdetificationReques();
        $hasFrontImage = $userAuth->hasFrontImage();
        $hasBackImage = $userAuth->hasBackImage();
        $requestIdentification = identificationuserrequest::where('idUser', $userAuth->idUser)
            ->where('status', StatusRequst::Rejected->value)
            ->latest('responseDate')
            ->first();
        if ($requestIdentification != null)
            $noteRequset = $requestIdentification->note;
        return view('livewire.identification-check',
            compact('user', 'usermetta_info', 'errors_array', 'userAuth', 'hasRequest', 'hasFrontImage', 'hasBackImage', 'noteRequset'))
            ->extends('layouts.master')->section('content');
    }

    public function sendIdentificationRequest(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $hasRequest = $userAuth->hasIdetificationReques();
        if ($hasRequest) {

            $this->dispatchBrowserEvent('existIdentificationRequest', [
                'tyepe' => 'warning',
                'title' => "Opt",
                'text' => '',
            ]);
        } else {

            $sensIdentification = identificationuserrequest::create([
                'idUser' => $userAuth->idUser,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'response' => 0,
                'note' => '',
                'status' => 1,
//        'responseDate'=>'',
            ]);
        }
    }
}
