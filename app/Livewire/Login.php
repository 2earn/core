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


        if (Auth::check()) {
            $this->redirect(route('home', ['locale' => app()->getLocale()]));
        } else {
            session(['oauth_state' => $this->state, 'oauth_nonce' => $this->nonce]);
            $params = http_build_query([
                'response_type' => 'code',
                'client_id' => config('services.auth_2earn.client_id'),
                'redirect_uri' => config('services.auth_2earn.redirect'),
                'scope' => 'openid',
                'state' => $this->state,
                'nonce' => $this->nonce,
            ]);

            $this->redirect(config('services.auth_2earn.authorise') . '?' . $params);
        }
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
