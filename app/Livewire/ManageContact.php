<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Models\ContactUser;
use App\Services\ContactUserService;
use App\Services\CountriesService;
use App\Services\settingsManager;
use App\Services\TransactionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Propaganistas\LaravelPhone\PhoneNumber;

class ManageContact extends Component
{
    protected ContactUserService $contactUserService;
    protected CountriesService $countriesService;

    public $contactId = null;
    public string $contactName = "";
    public string $contactLastName = "";
    public string $mobile = "";
    public $phoneCode = null;
    public $isEditMode = false;

    protected $rules = [
        'contactName' => 'required|string|min:3|max:255',
        'contactLastName' => 'required|string|min:3|max:255',
        'mobile' => 'required'
    ];

    protected $listeners = [
        'save' => 'save',
    ];

    public function boot(ContactUserService $contactUserService, CountriesService $countriesService)
    {
        $this->contactUserService = $contactUserService;
        $this->countriesService = $countriesService;
    }

    public function mount(Request $request, settingsManager $settingsManager)
    {
        $contactId = $request->input('contact');

        if ($contactId) {
            $this->isEditMode = true;
            $this->contactId = $contactId;

            $userContact = $this->contactUserService->findByIdAndUserId($contactId, auth()->user()->idUser);

            if (is_null($userContact)) {
                return redirect()->route('contacts_index', app()->getLocale())
                    ->with('danger', Lang::get('You are not allowed to edit this user contact'));
            }

            $this->contactName = $userContact->name;
            $this->contactLastName = $userContact->lastName ?? '';
            $this->mobile = $userContact->mobile;

            $user = $settingsManager->getUsers()
                ->where('idUser', $settingsManager->getAuthUser()->idUser)
                ->first();

            $phone = !empty($userContact->phonecode) ? $userContact->phonecode : $user->idCountry;
            $country = $this->countriesService->getCountryModelByPhoneCode($phone);

            if ($country) {
                $this->phoneCode = strtolower($country->apha2);
            }
        } else {
            // Add mode
            $this->isEditMode = false;
        }
    }

    public function resetForm()
    {
        $this->contactName = "";
        $this->contactLastName = "";
        $this->mobile = "";
        $this->phoneCode = null;
        $this->resetValidation();
    }

    public function cancel()
    {
        return redirect()->route('contacts_index', app()->getLocale());
    }

    public function validateContact()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $messages = [];

        if (empty($this->contactName)) {
            $messages[] = Lang::get('First name required');
        }
        if (empty($this->contactLastName)) {
            $messages[] = Lang::get('Last name required');
        }

        if (!empty($messages)) {
            return implode(", ", $messages);
        }

        return false;
    }

    public function save($phone, $ccode, $fullNumber, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $validationMessage = $this->validateContact();
        if ($validationMessage) {
            session()->flash('danger', $validationMessage);
            return;
        }

        $fullphone_number = str_replace(' ', '', $fullNumber);
        $mobile = str_replace(' ', '', $phone);
        $validatedPhone = false;

        try {
            $country = $this->countriesService->getByPhoneCode($ccode);
            $phoneObj = new PhoneNumber($fullNumber, $country->apha2);
            $phoneObj->formatForCountry($country->apha2);
            $validatedPhone = true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Phone Number does not match the provided country.'));
            return;
        }

        if ($validatedPhone) {
            try {
                $transactionManager->beginTransaction();

                if ($this->isEditMode) {
                    $user = $settingsManager->getUserByFullNumber($fullphone_number);

                    if (!$user) {
                        $user = $settingsManager->createNewUser(
                            $mobile,
                            $fullphone_number,
                            $ccode,
                            auth()->user()->idUser,
                            null
                        );
                    } else {

                        $ContactUser = $this->contactUserService->findByUserIdAndFullPhone(
                            $settingsManager->getAuthUser()->idUser,
                            $fullphone_number
                        );

                        if ($ContactUser) {
                            $transactionManager->rollback();
                            session()->flash('danger', Lang::get('Contact with same number ') . ' : ' . $ContactUser->name . ' ' . $ContactUser->lastName . ' : ' . $ContactUser->fullphone_number . ' ' . Lang::get('exists in the contact list'));
                            $this->dispatch('contactPhoneNeedsInit');
                            return;
                        }

                        if ($fullphone_number != $user->fullphone_number) {
                            $user = $settingsManager->updateUser(
                                $user,
                                $mobile,
                                $fullphone_number,
                                $ccode,
                                auth()->user()->idUser
                            );
                        }
                    }


                    $contact_user = new ContactUser([
                        'idUser' => auth()->user()->idUser,
                        'idContact' => $user->idUser,
                        'name' => $this->contactName,
                        'lastName' => $this->contactLastName,
                        'mobile' => $mobile,
                        'fullphone_number' => $fullphone_number,
                        'phonecode' => $ccode
                    ]);

                    $settingsManager->updateUserContactV2($this->contactId, $contact_user);
                    $transactionManager->commit();

                    return redirect()->route('contacts_index', app()->getLocale())
                        ->with('success', Lang::get('User updated') . ' : ' . $contact_user->name . ' ' . $contact_user->lastName . ' : ' . $contact_user->mobile);

                } else {
                    $contact_user_exist = $this->contactUserService->findByUserIdMobileAndCode(
                        $settingsManager->getAuthUser()->idUser,
                        $mobile,
                        $ccode
                    );

                    if ($contact_user_exist) {
                        $transactionManager->rollback();
                        session()->flash('danger', Lang::get('Contact with first name and last name') . ' : ' . $contact_user_exist->name . ' ' . $contact_user_exist->lastName . ' ' . Lang::get('exists in the contact list'));
                        $this->dispatch('contactPhoneNeedsInit');
                        return;
                    }

                    $user = $settingsManager->getUserByFullNumber($fullphone_number);

                    if (!$user) {
                        $user = $settingsManager->createNewUser(
                            $mobile,
                            $fullphone_number,
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
                        $mobile,
                        $fullphone_number,
                        $ccode
                    );

                    $transactionManager->commit();

                    Log::info('Contact added from Site 2earn :: code:' . $ccode . ' phone: ' . $mobile . ' fullNumber: ' . $fullphone_number);

                    return redirect()->route('contacts_index', app()->getLocale())
                        ->with('success', Lang::get('User created successfully') . ' : ' . $contact_user->name . ' ' . $contact_user->lastName . ' : ' . $contact_user->mobile);
                }

            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                $transactionManager->rollback();
                session()->flash('danger', Lang::get('User creation failed'));
            }
        }
    }

    public function render()
    {
        return view('livewire.manage-contact')->extends('layouts.master')->section('content');
    }
}
