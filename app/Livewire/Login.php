<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use Core\Services\settingsManager;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Login extends Component
{
    use RedirectsUsers;
    use earnLog;


    protected $listeners = [
        'login' => 'login',
        'changeLanguage' => 'changeLanguage'
    ];

    public ?string $from = null;
    public int $expireAt;

    public string $state;
    public string $nonce;
    public string $client_id;
    public string $loginUrl;

    public function mount(Request $request)
    {
        $this->from = $request->query->get('form');
        $this->expireAt = getSettingIntegerParam('EXPIRE_AT', 30);
        $this->state = bin2hex(random_bytes(16));
        $this->nonce = bin2hex(random_bytes(16));
        $this->loginUrl = "login url";
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
