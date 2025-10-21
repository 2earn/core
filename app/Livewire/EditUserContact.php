<?php

namespace App\Livewire;

use App\Models\ContactUser;
use Core\Models\countrie;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Propaganistas\LaravelPhone\PhoneNumber;

class EditUserContact extends Component
{

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
            $this->userCOntact = $this->userCOntact = ContactUser::where('id', $type)->where('idUser', auth()->user()->idUser)->first();
            if (is_null($this->userCOntact)) {
                return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('You are not allowed to edit this user contact'));
            }
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
            return redirect()->route('user_contact_edit', ['locale' => app()->getLocale(), "UserContact" => $this->idContact])->with('danger', $message);
        } else {
            $fullphone_number = str_replace(' ', '', $fullnumber);
            $mobile = str_replace(' ', '', $phone);
            $validatedPhone = false;
            try {
                $country = DB::table('countries')->where('phonecode', $code)->first();
                $phone = new PhoneNumber($fullnumber, $country->apha2);
                $phone->formatForCountry($country->apha2);
                $validatedPhone = true;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return redirect()->route('user_contact_edit', ['locale' => app()->getLocale(), "UserContact" => $this->idContact])->with('danger', Lang::get($exception->getMessage()));
            }
            if ($validatedPhone) {
                $user = $settingsManager->getUserByFullNumber($fullphone_number);
                if (!$user) {
                    $user = $settingsManager->createNewUser($mobile, $fullphone_number, $code, auth()->user()->idUser, null);
                } else {
                    if ($fullphone_number != $user->fullphone_number) {
                        $user = $settingsManager->updateUser($user, $mobile, $fullphone_number, $code, auth()->user()->idUser);
                    }
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
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                        $transactionManager->rollback();
                        Session::flash('message', 'failed');
                    }
                }
            }
        }
    }

    public function show($idd)
    {
        return redirect()->route('user_contact_edit', ['locale' => app()->getLocale(), 'idd' => $idd]);
    }


    public function render()
    {
        return view('livewire.edit-user-contact')->extends('layouts.master')->section('content');
    }
}
