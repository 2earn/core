<?php

namespace App\Http\Livewire;

use App\Models\ContactUser;
use App\Models\User;
use App\Services\Sponsorship\Sponsorship;
use App\Services\Sponsorship\SponsorshipFacade;
use Core\Models\Setting;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;
use Propaganistas\LaravelPhone\PhoneNumber;
use Livewire\Attributes\Url;


class Contacts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $deleteId;
    public string $contactName = "";
    public string $contactLastName = "";
    public string $mobile = "";
    public ?string $search = "";
    public ?string $pageCount = "100";
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

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'pageCount' => [
                'as' => 'pc',
            ],
        ];
    }

    private settingsManager $settingsManager;
    private TransactionManager $transactionManager;

    public function render(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $reservation = Setting::find(25);
        $switchBlock = Setting::find(29);
        if (!$userAuth) abort(404);
        $contactUserQuery = DB::table('contact_users as contact_users')
            ->join('users as u', 'contact_users.idContact', '=', 'u.idUser')
            ->join('countries as c', 'u.idCountry', '=', 'c.id')
            ->where('contact_users.idUser', $userAuth->idUser);
        if ($this->search != "") {
            $contactUserQuery = $contactUserQuery->where(function ($contactUserQuery) {
                $contactUserQuery->orWhere('contact_users.lastName', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_users.name', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_users.fullphone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_users.mobile', 'like', '%' . $this->search . '%');
            });
        }
        $contactUserQuery = $contactUserQuery->select('contact_users.id', 'contact_users.name', 'contact_users.lastName', 'contact_users.idUser', 'contact_users.idContact', 'contact_users.updated_at', 'u.reserved_by', 'u.mobile', 'u.availablity', 'c.apha2', 'u.idUpline', 'u.reserved_at',
            DB::raw("CASE WHEN u.status = -2 THEN 'warning' ELSE 'success' END AS color"),
            DB::raw("CASE WHEN u.status = -2 THEN 'Pending' ELSE 'User' END AS status"))
            ->orderBy('contact_users.updated_at', 'DESC');
        $contactUsers = $contactUserQuery->paginate($this->pageCount);
        $params = [
            'contactUsers' => $this->updateUsersContactList($settingsManager, $contactUsers, $reservation->IntegerValue, $switchBlock->IntegerValue),
        ];
        $this->resetPage();
        return view('livewire.contacts', $params)->extends('layouts.master')->section('content');
    }

    public function updateUsersContactList($settingsManager, $contactUsers, $reservation, $switchBlock)
    {
        $saleCcount = Setting::find(31);
        $sponsorshipRetardatifReservation = Setting::find(32);
        foreach ($contactUsers as $key => $contactUser) {
            $contactUser->canBeSponsored = false;
            $contactUser->canBeDisSponsored = false;
            $contactUser->sponsoredMessage = "no";
            $contactUser->sponsoredStatus = 'info';
            $user = $settingsManager->getUserByIdUser($contactUser->idContact);
            if ($contactUser->idUpline != 0) {
                if ($contactUser->idUpline == auth()->user()->idUser) {
                    if ($user->purchasesNumber < $saleCcount->IntegerValue) {
                        $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('I am his sponsor') . " " . ($saleCcount->IntegerValue - $user->purchasesNumber) . "" . Lang::get('purchases left'), 'info', false, false);
                    } else {
                        $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('I am his sponsor no commissions') . Lang::get('(No commissions)'), 'dark text-perple', false, false);
                    }
                } else {
                    if ($contactUser->idUpline == 11111111) {
                        $dateUpline = \DateTime::createFromFormat('Y-m-d H:i:s', $user->dateUpline);
                        $delaiDateUpline = $dateUpline->diff(now());
                        $diffDateUpline = ($delaiDateUpline->days * 24) + $delaiDateUpline->h;
                        if ($diffDateUpline < $sponsorshipRetardatifReservation->IntegerValue) {
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Available'), 'success', true, false);
                        } else {
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Already has a sponsor'), 'danger', false, false);
                        }
                    } else {
                        $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Already has a sponsor'), 'danger', false, false);
                    }
                }
            } else {
                if ($contactUser->availablity == 0) {
                    $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Available'), 'success', true, false);
                } else {
                    if (strtotime($contactUser->reserved_at)) {
                        $reserved_at = \DateTime::createFromFormat('Y-m-d H:i:s', $user->reserved_at);
                        $delai = $reserved_at->diff(now());
                        $diff = ($delai->days * 24) + $delai->h;
                        $reste = $reservation - $diff;
                    }
                    if ($contactUser->reserved_by == auth()->user()->idUser) {
                        if ($diff < $reservation) {
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Reserved for') . ' ' . $reste . ' ' . Lang::get('hours'), 'warning', false, true);
                        } else {
                            if (!is_null($user->reserved_at) and strtotime($user->reserved_at)) {
                                $reserved_at = \DateTime::createFromFormat('Y-m-d H:i:s', $user->reserved_at);
                                $interval = $reserved_at->diff(now());
                                $delai = ($interval->days * 24) + $interval->h;
                                $resteReserved = $reservation + $switchBlock - $delai;
                            } else {
                                $resteReserved = 0;
                            }
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('blocked for') . ' ' . $resteReserved . ' ' . Lang::get('hours'), 'warning', false, false);
                        }
                    } else {
                        if ($diff < $reservation) {
                            if (!is_null($user->reserved_at) and strtotime($user->reserved_at)) {
                                $reserved_at = \DateTime::createFromFormat('Y-m-d H:i:s', $user->reserved_at);
                                $interval = $reserved_at->diff(now());
                                $diff = ($delai->days * 24) + $delai->h;
                                $reste = $reservation - $diff;
                            } else {
                                $reste = 0;
                            }
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Reserved by other user for') . ' ' . $reste . ' ' . Lang::get('hours'), 'warning', false, false);

                        } else {
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Available'), 'success', true, false);
                        }
                    }
                }
            }
        }
        return $contactUsers;
    }

    public function updateUserContact($contactUser, $message, $status, $canSponsored, $canDisSponsored)
    {
        $contactUser->sponsoredMessage = $message;
        $contactUser->sponsoredStatus = $status;
        $contactUser->canBeSponsored = $canSponsored;
        $contactUser->canBeDisSponsored = $canDisSponsored;
        return $contactUser;
    }

    public function initUserContact($id, settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $ContactsUser = $this->settingsManager->getContactsUserById($id);
        if (!$ContactsUser) return;
        $this->selectedContect = $id;
        $this->contactName = $ContactsUser->name;
        $this->contactLastName = is_null($ContactsUser->lastName) ? '' : $ContactsUser->lastName;
        $this->mobile = $ContactsUser->mobile;
        $country = DB::table('countries')->where('apha2', $ContactsUser->phonecode)->first();
    }

    public function save($phone, $ccode, $fullNumber, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
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
            $contact_user = $settingsManager->createNewContactUser($settingsManager->getAuthUser()->idUser, $this->contactName, $user->idUser, $this->contactLastName, $phone, $fullNumber, $ccode,);
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('User created successfully') . ' : ' . $contact_user->name . ' ' . $contact_user->lastName . ' : ' . $contact_user->mobile);

        } catch (\Exception $exp) {
            if ($exp->getMessage() == "Number does not match the provided country.") {
                $this->transactionManager->rollback();
                return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('Phone Number does not match the provided country.'));
            } else {
                $this->transactionManager->rollback();
                return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('User creation failed'));
            }
        }
    }

    public function checkDelayedSponsorship($upLine, $downLine)
    {
        if (SponsorshipFacade::checkDelayedSponsorship($upLine, $downLine)) {
            SponsorshipFacade::executeDelayedSponsorship($upLine, $downLine);
        }
    }

    public function deleteContact($id)
    {
        $existeuser = ContactUser::find($id);
        $message = Lang::get('User deleted successfully') . ' : ' . $existeuser->name . ' ' . $existeuser->lastName . ' : ' . $existeuser->mobile;
        $existeuser->delete();
        return redirect()->route('contacts', app()->getLocale())->with('success', $message);
    }

    public function removeSponsoring($id, settingsManager $settingsManager)
    {
        $contactUser = ContactUser::where('id', $id)->get()->first();
        $settingsManager->removeSponsoring($contactUser->idContact);
        return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('Sponsorship removing operation completed successfully'));
    }


    public function sponsorId($id, settingsManager $settingsManager)
    {
        $contactUser = ContactUser::where('id', $id)->get()->first();
        if (!$settingsManager->checkCanSponsorship()) {
            return redirect()->route('contacts', app()->getLocale())->with('danger', Lang::get('Sponsorship operation failed : you reached the max'));
        }
        $upLine = $settingsManager->getUserByIdUser($contactUser->idUser);
        $downLine = $settingsManager->getUserByIdUser($contactUser->idContact);
        if ($upLine && $downLine) {
            $sponsoredUser = $settingsManager->addSponsoring($upLine, $downLine);
            $this->checkDelayedSponsorship($upLine, $downLine);
        }
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale())->with('success', Lang::get('Sponsorship operation completed successfully') . ' (' . $contactUser->name . ' ' . $contactUser->lastName . ': ' . $contactUser->fullphone_number . ')');
    }

    public function delete_multiple($ids)
    {
        $existeuser = ContactUser::whereIn('id', $ids)->delete();
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('contacts', app()->getLocale());
    }
}
