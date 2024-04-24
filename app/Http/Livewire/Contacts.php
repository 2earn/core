<?php

namespace App\Http\Livewire;

use App\Models\ContactUser;
use App\Models\User;
use App\Services\Sponsorship\Sponsorship;
use App\Services\Sponsorship\SponsorshipFacade;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;


class Contacts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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

    public function render(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $contactUserQuery = DB::table('contact_users as contact_users')
            ->join('users as u', 'contact_users.idContact', '=', 'u.idUser')
            ->join('countries as c', 'u.idCountry', '=', 'c.id')
            ->where('contact_users.idUser', $userAuth->idUser)
            ->select('contact_users.id', 'contact_users.name', 'contact_users.lastName', 'contact_users.idUser', 'contact_users.updated_at', 'u.reserved_by', 'u.mobile', 'u.availablity', 'c.apha2', 'u.idUpline', 'u.reserved_at',
                DB::raw("CASE WHEN u.status = -2 THEN 'warning' ELSE 'success' END AS color"),
                DB::raw("CASE WHEN u.status = -2 THEN 'Pending' ELSE 'User' END AS status"))
            ->orderBy('contact_users.updated_at', 'DESC');

        $contactUser = $contactUserQuery->paginate(10);
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
            return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('danger') . $contact_user_exist->name . ' ' . $contact_user_exist->lastName);
        }

        try {
            $user = $settingsManager->getUserByFullNumber($fullNumber);
            if (!$user) {
                $user = $settingsManager->createNewUser($this->mobile, $fullNumber, $ccode, auth()->user()->idUser);
            }
            $contact_user = $settingsManager->createNewContactUser($settingsManager->getAuthUser()->idUser, $this->name, $user->idUser, $this->lastName, $phone, $fullNumber, $ccode,);
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('User created successfully'));;
        } catch (\Exception $exp) {
            $this->transactionManager->rollback();
            return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('User creation failed'));;
        }
    }

    public function checkDelayedSponsorship($upLine, $downLine)
    {
        $sponsorUser = auth()->user();
        if (SponsorshipFacade::checkDelayedSponsorship($upLine, $downLine)) {
            SponsorshipFacade::executeDelayedSponsorship($upLine, $downLine);
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
            $this->checkDelayedSponsorship($upLine, $downLine);
        }
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('Sponsorship operation completed successfully') . ' (' . $contactUser->name . ' ' . $contactUser->lastName . ')');
    }

    public function delete_multiple($ids)
    {
        $existeuser = ContactUser::whereIn('id', $ids)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());
    }
}
