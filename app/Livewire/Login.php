<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()){
            $this->redirect(route('home'));
        } else {
            session(['oauth_state' => $this->state, 'oauth_nonce' => $this->nonce]);
            $params = http_build_query([
                'response_type' => 'code',
                'client_id' => config('app.auth_2earn_client_id'),
                'redirect_uri' => config('app.auth_2earn_redirect_url'),
                'scope' => 'openid',
                'state' => $this->state,
                'nonce' => $this->nonce,
            ]);

            $this->redirect(config('app.auth_2earn_authorise_url') . '?' . $params);
        }
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
