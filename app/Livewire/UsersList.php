<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class UsersList extends Component
{
    protected $listeners = [
        'changePassword' => 'changePassword'
    ];

    public function changePassword($id = null, $newPassword = null)
    {
        if (is_null($id) || is_null($newPassword)) {
            return redirect()->route('user_list', app()->getLocale())->with('warning', Lang::get('Bad password change credential'));
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

    public function render()
    {
        return view('livewire.user-list')->extends('layouts.master')->section('content');
    }
}






