<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Traits\earnLog;
use App\Models\MettaUser;
use App\Models\User;
use Core\Models\countrie;
use Core\Models\metta_user;
use Core\Models\user_earn;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
class EditUserAdmin extends Component
{


    public $userInfo=[];
    public $userId;
    public $states;
    public $newPassword;
    public $confirmedPassword;
    use earnLog;
    protected $listeners =['updatePhone514'=>'updatePhone',
    ];

    public function mount(Request $request)
    {
        $this->userId = $request->input('userId');
    }

    public function render()
    {

        $current_user = metta_user::where('idUser', $this->userId)->first();

        $this->userInfo['User'] = $current_user->enFirstName;

        $this->userInfo['UserLA'] = $current_user->enLastName;
        $this->userInfo['UserLast'] = $current_user->arLastName;
        $this->userInfo['UserFirst'] =$current_user->arFirstName;
        $this->userInfo['Email'] = $current_user->email;
        $this->userInfo['Datebirth'] = $current_user->birthday;
        $this->userInfo['Adresse'] = $current_user->adresse;
        $this->userInfo['Country'] = $current_user->idCountry;
        $this->userInfo['State'] = $current_user->idState;
        $this->userInfo['ChildrenCount'] =$current_user->childrenCount;
        $this->userInfo['Gender'] = $current_user->gender;
        $this->userInfo['Personaltitle'] = $current_user->personaltitle;
        $this->userInfo['Language'] = $current_user->idLanguage;
        $this->userInfo['nationalID'] = $current_user->nationalID;

        $Contry = countrie::all();
        $this->personaltitles = DB::table('personal_titles')->get()->toArray();
        $this->genders = DB::table('genders')->get();
        $this->states = DB::table('states')->get();
        $this->languages = DB::table('languages')->get();
//        dd($current_user) ;
        return view('livewire.edit-user-admin', [
            'countries' => $Contry
        ])->extends('layouts.master')->section('content');
    }


    public function saveUser(){
        $current_user = metta_user::where('idUser', $this->userId)->first();
        $current_user->enFirstName=$this->userInfo['User'];
        $current_user->enLastName=  $this->userInfo['UserLA'] ;
        $current_user->arLastName=$this->userInfo['UserLast'] ;
        $current_user->arFirstName=$this->userInfo['UserFirst'] ;
        $current_user->email=$this->userInfo['Email'] ;
        $current_user->birthday=$this->userInfo['Datebirth'] ;
        $current_user->adresse=$this->userInfo['Adresse'];
        $current_user->idCountry=$this->userInfo['Country'];
        $current_user->idState=$this->userInfo['State'];
        $current_user->childrenCount=$this->userInfo['ChildrenCount'];
        $current_user->gender=$this->userInfo['Gender'];
        $current_user->personaltitle=$this->userInfo['Personaltitle'];
        $current_user->idLanguage=$this->userInfo['Language'];
        $current_user->nationalID=$this->userInfo['nationalID'];
        $current_user->save();
        return redirect()->route('api_user_manager', app()->getLocale())->with('SuccesUpdateProfil', Lang::get('Succes_update'));

    }
    public function updatePassWord(){
        if ($this->newPassword==$this->confirmedPassword)
        {
            $current_user = User::where('idUser', $this->userId)->first();
            $current_user->password=Hash::make($this->newPassword);
            $current_user->save();
            return redirect()->route('api_user_manager', app()->getLocale())->with('SuccesUpdatePasswordUserAdmin', Lang::get('Password_updated'));
        }
        else
            dd('false');
    }
    public function checkUserByPhone($mobile, $idContry)
    {
        $user = DB::table('users')
            ->where([
                ['mobile', '=', $mobile],
                ['idCountry', '=', $idContry],
            ])
            ->get()->first();
        if (!$user)
            return null;
        return $user;
    }
    public function updatePhone($phoneNumber,$codePays,$fullNumber,settingsManager $settingsManager){

        $userAuth = $settingsManager->getAuthUser();

        $user=$this->checkUserByPhone($phoneNumber,$codePays);
        if ($user)
        {
            $this->earnDebug('update phone number user exist  :connected user id-' . $userAuth->idUser. ';Updated user id-'.$this->userId . ';phone Number-'.$phoneNumber .';code Pays-'.$codePays);
            return redirect()->route("adminUserEdit", ['locale'=>app()->getLocale(),'userId'=>$this->userId])->with('ErrorUpdatePhone', Lang::get('User exist'));
        }
        $userold=User::where('idUser',$this->userId)->first();
        $this->updatePhoneUserByAdmin($this->userId,$codePays,$phoneNumber,$fullNumber);
        $this->earnDebug('update phone number: update table user :connected user id-' . $userAuth->idUser. ';Updated user id-'.$this->userId .';Old phone Number-'.$userold->fullphone_number.';New Phone Number-'.$fullNumber);
        $this->updatePhoneUserEarnByAdmin($this->userId,$codePays,$phoneNumber,$fullNumber);
        $this->earnDebug('update phone number :update table user-earn :connected user id-' . $userAuth->idUser. ';Updated user id-'.$this->userId.';Old phone Number-'.$userold->fullphone_number .';New Phone Number-'.$fullNumber);
        $this->dispatchBrowserEvent('updatePhone', [
            'text' => 'success update',
        ]);
    }
    public function updatePhoneUserByAdmin($userid,$codecountry,$phoneNumber,$fullNumber){
        $user=User::where('idUser',$userid)->first();
        $user->idCountry=$codecountry ;
        $user->mobile=$phoneNumber ;
        $user->fullphone_number=$fullNumber;
        $user->save();
    }
    public function updatePhoneUserEarnByAdmin($userid,$codecountry,$phoneNumber,$fullNumber){
        $user=user_earn::where('idUser',$userid)->first();
        $user->idCountry=$codecountry ;
        $user->mobile=$phoneNumber ;
        $user->fullphone_number=$fullNumber;
        $user->save();
    }
}
