<?php

namespace App\Http\Livewire;


use App\Http\Traits\earnLog;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\String\b;


class ChangePassword extends Component
{
    public $idUser;
    use earnLog;
    public $newPassword;
    public $confirmedPassword;

    protected $rules = [
        'newPassword' => [
            'required',
            'regex:/[0-9]/'
        ],
        'confirmedPassword' => 'required|same:newPassword'
    ];
    public function mount($idUser)
    {
        $this->idUser = Crypt::decryptString($idUser);
    }
    public function render()
    {
        return view('livewire.change-password')->extends('layouts.master-without-nav')->section('content');
    }
    public function change(settingsManager $settingsManager)
    {

        $this->validate();
        $user = $settingsManager->getUsers()->where('idUser', $this->idUser)->first();
        if ( !$user)
        {
            $this->earnDebug('update password user not found  : iduser- ' .$this->idUser);
            dd($user);
        }
        $new_pass = Hash::make($this->newPassword);
        DB::table('users')->where('id', $user->id)->update(['password' => $new_pass]);
        $this->earnDebug('update password password updated  : iduser- ' .$this->idUser);
        return redirect()->route('login', app()->getLocale())->with('SuccesUpdatePassword', 'Password updated');


    }
}
