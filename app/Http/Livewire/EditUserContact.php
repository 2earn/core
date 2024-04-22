<?php

namespace App\Http\Livewire;

use App\Models\ContactUser;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

use Core\Models\countrie;
use Core\Models\UserContact;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EditUserContact extends Component
{
    public $idd;
    public $locc;
    public $idContact;
    public $userCOntact;
    public $nameUserContact;
    public $lastNameUserContact;
    public $phoneCode;
    public $phoneNumber;
    public $listeners = [
        'save' => 'save',
        'close' => 'close'
    ];

    public function mount(Request $request, settingsManager $settingsManager)
    {
        $type = $request->input('UserContact');
        if ($type) {
            $this->userCOntact = $this->userCOntact = ContactUser::find($type);
            $this->nameUserContact = $this->userCOntact->name;
            $this->lastNameUserContact = $this->userCOntact->lastName;
            $this->phoneNumber = $this->userCOntact->mobile;
            $user = $settingsManager->getUsers()->where('idUser', $settingsManager->getAuthUser()->idUser)->first();
            $phone = !empty($this->userCOntact->phonecode) ? $this->userCOntact->phonecode : $user->idCountry;
            $this->idContact = $type;
            $country = strval(countrie::where('phonecode', $phone)->first()->apha2);
            $this->phoneCode = $country;
        }

    }

    public function render()
    {
        return view('livewire.edit-user-contact')->extends('layouts.master')->section('content');
    }

    public function close()
    {
        return redirect()->route('contacts', app()->getLocale());

    }

    public function save($code, $fullnumber, $phone, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $fullphone_number = str_replace(' ', '', str_ends_with($fullnumber, $phone) ? $fullnumber : $fullnumber . $phone);
        $mobile = str_replace(' ', '', $phone);
        $user = $settingsManager->getUserByFullNumber($fullphone_number);
        $fullName = $this->nameUserContact . ' ' . $this->lastNameUserContact;

        if (!$user) {
            $user = $settingsManager->createNewUser($fullName, $mobile, $fullphone_number, $code, auth()->user()->idUser);
        } else {
            $user = $settingsManager->updateUser($user, $fullName, $mobile, $fullphone_number, $code, auth()->user()->idUser);
        }
        $contact_user = new ContactUser([
            'idUser' => Auth()->user()->idUser,
            'idContact' => $user->idUser,
            'name' => $this->nameUserContact,
            'lastName' => $this->lastNameUserContact,
            'mobile' => $mobile,
            'fullphone_number' => $fullphone_number,
            'phonecode' => $code
        ]);
        $existeuser = ContactUser::where('id', $this->idContact)->get()->first();
        if ($existeuser) {
            try {
                $transactionManager->beginTransaction();
                $settingsManager->updateUserContactV2($this->idContact, $contact_user);
                $transactionManager->commit();
                return redirect()->route('contacts', app()->getLocale())->with('SessionUserUpdated',  Lang::get('User updated'));
            } catch (\Exception $exp) {
                $transactionManager->rollback();
                Session::flash('message', 'failed');
            }
        }
    }

    public function show($idd)
    {
        return view('livewire.edit-user-contact',
            [
                'idd' => "ee"
            ]
        )->with('idd', 'dfdf');
        return redirect()->route('editContact', ['locale' => app()->getLocale(), 'idd' => $idd]);

    }
}
