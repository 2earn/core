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
            ->select('contact_users.id', 'contact_users.name', 'contact_users.lastName', 'contact_users.idUser', 'u.mobile', 'u.availablity', 'c.apha2',
                DB::raw("CASE WHEN u.status = -2 THEN 'bg-warning-subtle text-warning' ELSE 'bg-success-subtle text-success' END AS color"),
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
            return redirect()->route('contacts', app()->getLocale())->with('existeUserContact', 'deja existe')->with('sessionIdUserExiste', $contact_user_exist->id);
        }

        $contact_user__user = $settingsManager->getUserByFullNumber($fullNumber);

        if (!$contact_user__user) {
            $contact_user__user = $settingsManager->createNewUser(
                $this->name . ' ' . $this->lastName,
                $this->mobile,
                $fullNumber,
                $ccode
            );
        }
        $contact_user = $settingsManager->createNewContactUser($settingsManager->getAuthUser()->idUser, $this->name, $contact_user__user->idUser, $this->lastName, $phone, $fullNumber, $ccode,);
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


    public function update($id, $name, $lastName, $mobile, TransactionManager $transactionManager)
    {
        $existeuser = UserContact::where('id', $id)
            ->get()
            ->first();
        if ($existeuser) {
            try {
                $existeuser->name = $name;
                $existeuser->lastName = $lastName;
                $existeuser->mobile = $mobile;
                $existeuser->save();
                $this->dispatchBrowserEvent('close-modal');
                return redirect()->route('contacts', app()->getLocale());

            } catch (\Exception $exp) {
                $transactionManager->rollback(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
                dd($exp);
                Session::flash('message', 'failed');
            }
        } else {
            dd("error");
        }
    }

    public function deleteId($id)
    {
        $existeuser = UserContact::where('id', $id)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());

    }

    public function delete_multiple($ids)
    {
        $existeuser = UserContact::whereIn('id', $ids)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());
    }
}
