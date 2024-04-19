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
        'inituserContact' => 'initUserContact',
        'deleteContact' => 'deleteContact',
        'deleteId' => 'deleteId',
        'save' => 'save',
        'edit' => 'edit',
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
        $this->name = "";
        $this->lastName = "";
        $this->mobile = "";
    }

    public function initUserInfo()
    {
        $this->Infosuser = [];
        $Infuser = new  MyAccountViewModel();
        $Infuser->idUser = $this->settingsManager->getAuthUser()->idUser;
        array_push($this->Infosuser, $Infuser);
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
        $this->idC = $id;
        $userC = $this->settingsManager->getUserContactsById($id);

        if (!$userC) return;
        $this->selectedContect = $id;
        $this->name = $userC->name;
        $this->lastName = is_null($userC->lastName) ? '' : $userC->lastName;
        $this->mobile = $userC->mobile;
        $this->userC = $userC;
        $country = DB::table('countries')->where('apha2', $userC->phonecode)->first();
        dd($country);
        return redirect()->route('myAccount', app()->getLocale())->with('toEditForm', ' ');

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
            return redirect()->route('contacts', app()->getLocale());
            return response(['message' => "User created successfully", 'status' => "success"], 200);
        } catch (\Exception $exp) {
            $this->transactionManager->rollback(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            Session::flash('message', 'failed');
        }
    }

    public function initUserInfo2()
    {
        $this->Infosuser = [];
        $Infuser = new  MyAccountViewModel();
        $account_info = DB::table('metta_users')
            ->where('idUser', '=', '197604161')->first();
        $userearn = DB::table('user_earns')
            ->where('idUser', '=', '197604161')->first();

        $Infuser->idUser = $account_info->id;
        $Infuser->enFirstName = $account_info->enFirstName;
        $Infuser->enLastName = $account_info->enLastName;
        $Infuser->mobile = $userearn->mobile;
        $Infuser->email = $account_info->email;
        $Infuser->adresse = $account_info->adresse;
        $Infuser->userContact = DB::table('contact_users')
            ->where('idUser', 197604161)->get();
        array_push($this->Infosuser, $Infuser);
    }


    public function edit($id, $name, $lastName, $mobile, TransactionManager $transactionManager)
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
