<?php

namespace App\Livewire;

use App\Models\ContactUser;
use App\Services\Sponsorship\SponsorshipFacade;
use App\Models\Setting;
use App\Services\settingsManager;
use App\Services\TransactionManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;


class Contacts extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $deleteId;
    public ?string $search = "";
    public ?string $pageCount = "10";

    protected $listeners = [
        'deleteContact' => 'deleteContact',
        'deleteId' => 'deleteId',
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
                        $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('I am his sponsor') . " " . ($saleCcount->IntegerValue - $user->purchasesNumber) . " " . Lang::get('purchases left'), 'info', false, false);
                    } else {
                        $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('I am his sponsor no commissions') . Lang::get('(No commissions)'), 'dark text-perple', false, false);
                    }
                } else {
                    if ($contactUser->idUpline == 11111111) {
                        $dateUpline = \DateTime::createFromFormat(config('app.date_format'), $user->dateUpline);
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
                        $reserved_at = \DateTime::createFromFormat(config('app.date_format'), $user->reserved_at);
                        $delai = $reserved_at->diff(now());
                        $diff = ($delai->days * 24) + $delai->h;
                        $reste = $reservation - $diff;
                    }
                    if ($contactUser->reserved_by == auth()->user()->idUser) {
                        if ($diff < $reservation) {
                            $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Reserved for') . ' ' . $reste . ' ' . Lang::get('hours'), 'warning', false, true);
                        } else {
                            if (!is_null($user->reserved_at) and strtotime($user->reserved_at)) {
                                $reserved_at = \DateTime::createFromFormat(config('app.date_format'), $user->reserved_at);
                                $interval = $reserved_at->diff(now());
                                $delai = ($interval->days * 24) + $interval->h;
                                $resteReserved = $reservation + $switchBlock - $delai;
                            } else {
                                $resteReserved = 0;
                            }
                            if ($resteReserved > 0)
                                $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('blocked for') . ' ' . $resteReserved . ' ' . Lang::get('hours'), 'warning', false, false);
                            else $contactUsers[$key] = $this->updateUserContact($contactUser, Lang::get('Available'), 'success', true, false);
                        }
                    } else {
                        if ($diff < $reservation) {
                            if (!is_null($user->reserved_at) and strtotime($user->reserved_at)) {
                                $reserved_at = \DateTime::createFromFormat(config('app.date_format'), $user->reserved_at);
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
        return redirect()->route('contacts_index', app()->getLocale())->with('success', $message);
    }

    public function removeSponsoring($id, settingsManager $settingsManager)
    {
        $contactUser = ContactUser::where('id', $id)->get()->first();
        $settingsManager->removeSponsoring($contactUser->idContact);
        return redirect()->route('contacts_index', app()->getLocale())->with('success', Lang::get('Sponsorship removing operation completed successfully'));
    }


    public function sponsorId($id, settingsManager $settingsManager)
    {
        $contactUser = ContactUser::where('id', $id)->get()->first();
        if (!$settingsManager->checkCanSponsorship()) {
            return redirect()->route('contacts_index', app()->getLocale())->with('danger', Lang::get('Sponsorship operation failed : you reached the max'));
        }
        $upLine = $settingsManager->getUserByIdUser($contactUser->idUser);
        $downLine = $settingsManager->getUserByIdUser($contactUser->idContact);
        if ($upLine && $downLine) {
            $sponsoredUser = $settingsManager->addSponsoring($upLine, $downLine);
            $this->checkDelayedSponsorship($upLine, $downLine);
        }
        $this->dispatch('close-modal');
        return redirect()->route('contacts_index', app()->getLocale())->with('success', Lang::get('Sponsorship operation completed successfully') . ' (' . $contactUser->name . ' ' . $contactUser->lastName . ': ' . $contactUser->fullphone_number . ')');
    }

    public function delete_multiple($ids)
    {
        ContactUser::whereIn('id', $ids)->delete();
        $this->dispatch('close-modal');
        return redirect()->route('contacts_index', app()->getLocale());
    }

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
        $contactUserQuery = $contactUserQuery->select('contact_users.id', 'contact_users.name', 'contact_users.lastName', 'contact_users.idUser', 'contact_users.idContact', 'contact_users.created_at', 'contact_users.updated_at', 'u.reserved_by', 'u.status', 'u.mobile', 'u.availablity', 'c.apha2', 'u.idUpline', 'u.reserved_at')
            ->orderBy('contact_users.updated_at', 'DESC');
        $contactUsers = $contactUserQuery->paginate($this->pageCount);
        $params = [
            'contactUsers' => $this->updateUsersContactList($settingsManager, $contactUsers, $reservation->IntegerValue, $switchBlock->IntegerValue),
        ];
        $this->resetPage();
        return view('livewire.contacts', $params)->extends('layouts.master')->section('content');
    }

}
