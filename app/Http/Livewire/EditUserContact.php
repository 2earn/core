<?php

namespace App\Http\Livewire;

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
        'save' => 'save'
    ];

    public function mount(Request $request, settingsManager $settingsManager)
    {
        $type = $request->input('UserContact');
        $this->userCOntact = $this->userCOntact = UserContact::find($type);
        $this->nameUserContact = $this->userCOntact->name;
        $this->lastNameUserContact = $this->userCOntact->lastName;
        $this->phoneNumber = $this->userCOntact->mobile;
        $user = $settingsManager->getUsers()->where('idUser', $settingsManager->getAuthUser()->idUser)->first();
        $phone = !empty($this->userCOntact->phonecode) ? $this->userCOntact->phonecode : $user->idCountry;
        $this->idContact = $type;
        $countrie = strval(countrie::where('phonecode', $phone)->first()->apha2);
        $this->phoneCode = $countrie;
//        dd($this->phoneNumber) ;
    }

    public function render()
    {
        return view('livewire.edit-user-contact')->extends('layouts.master')->section('content');
    }

    public function save($ccode, $fullNumber,
                         settingsManager $settingsManager,
                         TransactionManager $transactionManager
    )
    {
        $userc = new UserContact([
            'idUser' => $settingsManager->getAuthUser()->idUser,
            'name' => $this->nameUserContact,
            'lastName' => $this->lastNameUserContact,
            'mobile' => $this->phoneNumber,
            'fullphone_number' => $fullNumber,
            'phonecode' => $ccode,
            'availablity' => 'AVAILABLE',
            'disponible' => 1
        ]);
        $userc->id = $this->idContact;
        $existeuser = UserContact::where('id', $this->idContact)
            ->get()
            ->first();
        if ($existeuser) {
            try {
                $transactionManager->beginTransaction();
                $settingsManager->updateUserContact($userc);
                $transactionManager->commit();
                return redirect()->route('contacts', app()->getLocale())->with('SessionUserUpdated', 'user updated');
            } catch (\Exception $exp) {
                $transactionManager->rollback(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
                dd($exp);
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
