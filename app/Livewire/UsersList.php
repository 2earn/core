<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\vip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    protected $listeners = [
        'changePassword' => 'changePassword'
    ];

    public $search = '';
    public $pageCount = 20;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'pageCount'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPageCount()
    {
        $this->resetPage();
    }

    public function changePassword($id = null, $newPassword = null)
    {
        if (is_null($id) || is_null($newPassword) || empty($id) || empty($newPassword)) {
            return redirect()->route('user_list', app()->getLocale())->with('warning', Lang::get('Bad password change credential'));
        }
        if (strlen($newPassword) < 8) {
            return redirect()->route('user_list', app()->getLocale())->with('warning', Lang::get('Short password (8 characters minimum)'));
        }

        if ($id == auth()->user()->id) {
            return redirect()->route('user_list', app()->getLocale())->with('warning', Lang::get('You chant change the password of the current user'));
        }
        $user = User::find($id);
        $new_pass = Hash::make($newPassword);
        DB::table('users')->where('id', $id)
            ->update(['password' => $new_pass]);

        return redirect()->route('user_list', app()->getLocale())->with('success', Lang::get('Password updated successfully for user') . ' : ' . getUserDisplayedName($user->idUser));

    }

    public function getUsers()
    {
        $query = User::select(
            'countries.apha2',
            'countries.name as country',
            'users.id',
            'users.status',
            'users.idUser',
            'idUplineRegister',
            DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName)) AS name'),
            'users.mobile',
            'users.created_at',
            'OptActivation',
            'activationCodeValue',
            'pass',
            DB::raw('IFNULL(`vip`.`flashCoefficient`,"##") as coeff'),
            DB::raw('IFNULL(`vip`.`flashDeadline`,"##") as periode'),
            DB::raw('IFNULL(`vip`.`flashNote`,"##") as note'),
            DB::raw('IFNULL(`vip`.`flashMinAmount`,"##") as minshares'),
            DB::raw('`vip`.`dateFNS` as date')
        )
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry')
            ->leftJoin('vip', function ($join) {
                $join->on('vip.idUser', '=', 'users.idUser')->where('vip.closed', '=', 0);
            });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('users.mobile', 'like', '%' . $this->search . '%')
                    ->orWhere('users.idUser', 'like', '%' . $this->search . '%')
                    ->orWhere(DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName))'), 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate($this->pageCount);
    }

    public function render()
    {
        $users = $this->getUsers();

        return view('livewire.user-list', [
            'users' => $users
        ])->extends('layouts.master')->section('content');
    }
}






