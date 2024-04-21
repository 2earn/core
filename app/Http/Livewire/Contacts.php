<?php

namespace App\Http\Livewire;

use App\DAL\LanguageRepository;
use App\Models\ContactUser;
use App\Models\User;
use Core\Models\UserContact;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Session;

class Contacts extends Component
{
    public $deleteId;
    public string $name = "";
    public string $lastName = "";
    public string $mobile = "";
    public $selectedContect;

    protected $rules = [
        'name' => 'required',
        'lastName' => 'required',
        'mobile' => 'required'
    ];

    protected $listeners = [
        'initUserContact' => 'initUserContact',
        'updateContact' => 'updateContact',
        'deleteContact' => 'deleteContact',
        'deleteId' => 'deleteId',
        'save' => 'save',
        'update' => 'update',
        'initNewUserContact' => 'initNewUserContact',
        'delete_multiple' => 'delete_multiple'
    ];


    private settingsManager $settingsManager;
    private TransactionManager $transactionManager;

    public function mount()
    {
    }

    public function render(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $contactUserQuery = DB::table('contact_users as contact_users')
            ->join('users as u', 'contact_users.idContact', '=', 'u.idUser')
            ->join('countries as c', 'u.idCountry', '=', 'c.id')
            ->where('contact_users.idUser', $userAuth->idUser)
            ->select('contact_users.id', 'contact_users.name', 'contact_users.lastName', 'contact_users.idUser', 'u.reserved_by', 'u.mobile', 'u.availablity', 'c.apha2', 'u.idUpline', 'u.reserved_at',
                DB::raw("CASE WHEN u.status = -2 THEN 'warning' ELSE 'success' END AS color"),
                DB::raw("CASE WHEN u.status = -2 THEN 'Pending' ELSE 'User' END AS status"));

        $contactUser = $contactUserQuery->get();
        return view('livewire.contacts', ['contactUser' => $contactUser])->extends('layouts.master')->section('content');
    }

    public function delete(settingsManager $settingsManager)
    {
        User::find($this->deleteId)->delete();
        $userC = $settingsManager->getUserContactsById($this->deleteId);
        if (!$userC) return;
        $userC->delete();
        return redirect()->route('contacts', app()->getLocale());
    }

    public function initNewUserContact(settingsManager $settingsManager)
    {
        $this->id = "";
        $this->name = "";
        $this->lastName = "";
        $this->mobile = "";
    }

    public function deleteContact($id, settingsManager $settingsManager)
    {
        $userC = $settingsManager->getUserContactsById($id);
        if (!$userC) return;
        $userC->delete();
        return redirect()->route('contacts', app()->getLocale());
    }

    public function initUserContact($id, settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $ContactsUser = $this->settingsManager->getContactsUserById($id);
        if (!$ContactsUser) return;
        $this->selectedContect = $id;
        $this->name = $ContactsUser->name;
        $this->lastName = is_null($ContactsUser->lastName) ? '' : $ContactsUser->lastName;
        $this->mobile = $ContactsUser->mobile;
        $country = DB::table('countries')->where('apha2', $ContactsUser->phonecode)->first();
    }

    public function save($phone, $ccode, $fullNumber, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $this->settingsManager = $settingsManager;
        $this->transactionManager = $transactionManager;
        $this->validate();

        $contact_user_exist = ContactUser::where('idUser', $settingsManager->getAuthUser()->idUser)
            ->where('mobile', $phone)
            ->where('phonecode', $ccode)
            ->get()->first();

        if ($contact_user_exist) {
            return redirect()->route('contacts', app()->getLocale())->with('existeUserContact', 'deja existe')->with('message', 'sessionIdUserExiste', $contact_user_exist->id);
        }

        $user = $settingsManager->getUserByFullNumber($fullNumber);

        if (!$user) {
            $user = $settingsManager->createNewUser(
                $this->name . ' ' . $this->lastName,
                $this->mobile,
                $fullNumber,
                $ccode,
                auth()->user()->idUser
            );
        }else{
            $user = $settingsManager->updateUser(
                $user,
                $this->name . ' ' . $this->lastName,
                $this->mobile,
                $fullNumber,
                $ccode,
                auth()->user()->idUser
            );
        }

        $contact_user = $settingsManager->createNewContactUser($settingsManager->getAuthUser()->idUser, $this->name, $user->idUser, $this->lastName, $phone, $fullNumber, $ccode,);
        try {
            $this->transactionManager->beginTransaction();
            $this->settingsManager->addUserContactV2($contact_user);
            $this->transactionManager->commit();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->route('contacts', app()->getLocale())->with('message', 'User created successfully');;
        } catch (\Exception $exp) {
            $this->transactionManager->rollback();
            return redirect()->route('contacts', app()->getLocale())->with('alert-class', 'User creation failed');;
        }
    }

    public function executeDelayedSponsorship($sponsoredUser)
    {
        // NOTE TO DO : executeDelayedSponsorship
    }

    public function checkDelayedSponsorship($sponsoredUser)
    {
        // NOTE TO DO : checkDelayedSponsorship
        $sponsorUser =  auth()->user();
        if (true) {
            $this->executeDelayedSponsorship($sponsoredUser);
        }
    }


    public function deleteId($id)
    {
        $existeuser = ContactUser::where('id', $id)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());
    }

    public function sponsorId($id, settingsManager $settingsManager)
    {
        $contactUser = ContactUser::where('id', $id)->get()->first();
        $upLine = $settingsManager->getUserByIdUser($contactUser->idUser);
        $downLine = $settingsManager->getUserByIdUser($contactUser->idContact);
        if ($upLine && $downLine) {
            $sponsoredUser = $settingsManager->addSponsoring($upLine, $downLine);
            if ($downLine) {
                $this->checkDelayedSponsorship($downLine);
            }
        }
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale())->with('message', 'Sponsorship Success');
    }


    public function delete_multiple($ids)
    {
        $existeuser = ContactUser::whereIn('id', $ids)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());
    }
}
