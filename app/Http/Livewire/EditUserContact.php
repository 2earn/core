<?php

namespace App\Http\Livewire;

use App\Models\ContactUser;
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
            $countrie = strval(countrie::where('phonecode', $phone)->first()->apha2);
            $this->phoneCode = $countrie;
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

        $contact_user = new ContactUser([
            'name' => $this->nameUserContact,
            'lastName' => $this->lastNameUserContact,
            'mobile' => str_replace(' ', '', $phone),
            'fullphone_number' => $fullphone_number,
            'phonecode' => $code
        ]);

        $existeuser = ContactUser::where('id', $this->idContact)
            ->get()
            ->first();
        if ($existeuser) {
            try {
                $transactionManager->beginTransaction();
                $settingsManager->updateUserContactV2($this->idContact, $contact_user);
                $transactionManager->commit();
                return redirect()->route('contacts', app()->getLocale())->with('SessionUserUpdated', 'User updated');
            } catch (\Exception $exp) {
                $transactionManager->rollback(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
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
        return redirect()->route('editContact2', ['locale' => app()->getLocale(), 'idd' => $idd]);

    }
}
