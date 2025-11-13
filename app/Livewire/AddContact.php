<?php

namespace App\Livewire;

use App\Models\ContactUser;
use Core\Enum\StatusRequest;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddContact extends Component
{
    public string $contactName = "";
    public string $contactLastName = "";
    public string $mobile = "";

    protected $rules = [
        'contactName' => 'required|string|max:255',
        'contactLastName' => 'required|string|max:255',
        'mobile' => 'required'
    ];

    protected $listeners = [
        'save' => 'save',
    ];

    public function resetForm()
    {
        $this->contactName = "";
        $this->contactLastName = "";
        $this->mobile = "";
        $this->resetValidation();
    }

    public function cancel()
    {
        return redirect()->route('contacts', app()->getLocale());
    }

    public function save($phone, $ccode, $fullNumber, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'contactLastName' => 'required|string|max:255',
        ]);

        $contact_user_exist = ContactUser::where('idUser', $settingsManager->getAuthUser()->idUser)
            ->where('mobile', $phone)
            ->where('phonecode', $ccode)
            ->first();

        if ($contact_user_exist) {
            session()->flash('danger', Lang::get('Contact with first name and last name') . ' : ' . $contact_user_exist->name . ' ' . $contact_user_exist->lastName . ' ' . Lang::get('exists in the contact list'));
            return;
        }

        try {
            $user = $settingsManager->getUserByFullNumber($fullNumber);

            if (!$user) {
                $user = $settingsManager->createNewUser(
                    str_replace(' ', '', $this->mobile),
                    $fullNumber,
                    $ccode,
                    auth()->user()->idUser,
                    StatusRequest::ContactRegistred->value
                );
            }

            $contact_user = $settingsManager->createNewContactUser(
                $settingsManager->getAuthUser()->idUser,
                $this->contactName,
                $user->idUser,
                $this->contactLastName,
                $phone,
                $fullNumber,
                $ccode
            );

            Log::info('Contact added from Site 2earn :: code:' . $ccode . ' phone: ' . $phone . ' fullNumber: ' . $fullNumber);

            return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('User created successfully') . ' : ' . $contact_user->name . ' ' . $contact_user->lastName . ' : ' . $contact_user->mobile);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            if ($exception->getMessage() == "Number does not match the provided country.") {
                $errorMessage = Lang::get('Phone Number does not match the provided country.');
            } else {
                $errorMessage = Lang::get('User creation failed');
            }

            session()->flash('danger', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.add-contact')->extends('layouts.master')->section('content');
    }
}

