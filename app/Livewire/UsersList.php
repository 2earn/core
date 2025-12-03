<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserService;
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

    }

    public function render()
    {
        $userService = app(UserService::class);
        $users = $userService->getUsers($this->search, $this->sortBy, $this->sortDirection, $this->pageCount);
        return view('livewire.user-list', ['users' => $users])->extends('layouts.master')->section('content');
    }
}






