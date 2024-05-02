<?php

namespace App\Http\Livewire;

use App\Models\ContactUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

use Core\Models\countrie;
use Core\Models\UserContact;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Propaganistas\LaravelPhone\PhoneNumber;
use function PHPUnit\Framework\isEmpty;

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

    protected $rules = [
        'nameUserContact' => 'required|min:3',
        'lastNameUserContact' => 'required|min:3',
    ];

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
            $country = countrie::where('phonecode', $phone)->first()->apha2;
            $this->phoneCode = strtolower($country);
        }
    }

    public function render()
    {
        return view('livewire.edit-user-contact')->extends('layouts.master')->section('content');
    }

    public function validateContact()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $messages = [];
        if (empty($this->nameUserContact)) {
            $messages[] = Lang::get('First name required');
        }
        if (empty($this->lastNameUserContact)) {
            $messages[] = Lang::get('Last name required');
        }
        if (!empty($messages)) {
            $result = "";
            foreach ($messages as $message) {
                $result .= $message;
                if (next($messages)) $result .= ", ";
            }
            return $result;
        }
        return false;
    }

    public function close()
    {
        return redirect()->route('contacts', app()->getLocale());
    }

    public function save($code, $fullnumber, $phone, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $message = $this->validateContact();
        if ($message) {
            return redirect()->route('editContact', ['locale' => app()->getLocale(), "UserContact" => $this->idContact])->with('danger', $message);
        } else {
            $fullphone_number = str_replace(' ', '', $fullnumber);
            $mobile = str_replace(' ', '', $phone);
            $validatedPhone = false;
            try {
                $country = DB::table('countries')->where('phonecode', $code)->first();
                $phone = new PhoneNumber($fullnumber, $country->apha2);
                $phone->formatForCountry($country->apha2);
                $validatedPhone = true;
            } catch (\Exception $exp) {
                return redirect()->route('editContact', ['locale' => app()->getLocale(), "UserContact" => $this->idContact])->with('danger', Lang::get($exp->getMessage()));
            }
            if ($validatedPhone) {
                $user = $settingsManager->getUserByFullNumber($fullphone_number);
                if (!$user) {
                    $user = $settingsManager->createNewUser($mobile, $fullphone_number, $code, auth()->user()->idUser);
                } else {
                    $user = $settingsManager->updateUser($user, $mobile, $fullphone_number, $code, auth()->user()->idUser);
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
                        return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('User updated') . ' : ' . $contact_user->name . ' ' . $contact_user->lastName . ' : ' . $contact_user->mobile);
                    } catch (\Exception $exp) {
                        $transactionManager->rollback();
                        Session::flash('message', 'failed');
                    }
                }
            }
        }
    }

    public
    function show($idd)
    {
        return view('livewire.edit-user-contact',
            [
                'idd' => "ee"
            ]
        )->with('idd', 'dfdf');
        return redirect()->route('editContact', ['locale' => app()->getLocale(), 'idd' => $idd]);

    }
}
