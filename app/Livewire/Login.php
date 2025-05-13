<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use Core\Services\settingsManager;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Login extends Component
{
    use RedirectsUsers;
    use earnLog;

    const MAX_ATTEMPTS = 3;
    const RATE_KEY = 'login-attempts-';
    protected $listeners = [
        'login' => 'login',
        'changeLanguage' => 'changeLanguage'
    ];

    public ?string $from = null;

    public function mount(Request $request)
    {
        $this->from = $request->query->get('form');
    }

    public function login($number, $code, $pass, $iso, settingsManager $settingsManager, Request $request)
    {
        $key = self::RATE_KEY . $number;
        $blockedUserKey ='blocked-user-' . $number;

        if (Cache::has($blockedUserKey)) {
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Too many login attempts. Please try again later.'));
        }
        Log::info(Cache::has($blockedUserKey));

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            Cache::put($blockedUserKey, true, now()->addMinutes(10));
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Too many failed attempts! You are temporarily blocked.'));
        }
        Log::info(RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS));

        if ($number == "" || $code == "" || $pass == "" || $iso == "" || strlen($iso) > 2) {
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Your phone number or password is incorrect!'));
        }

        $user = $settingsManager->loginUser(str_replace(' ', '', $number), $code, false, $pass, $iso);
        if (!$user) {
            RateLimiter::hit($key, 60); // Increment failed attempt count with a 60-second cooldown
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Your phone or password is incorrect!'));
        }

        RateLimiter::clear($key);
        Cache::forget('blocked-user-' . $number);

        if (!is_null($this->from)) {
            Log::info('Inscription from Site 2earn :: code:' . $code . ' number: ' . $number);
            return redirect()->intended(route('home', app()->getLocale()))->with('from', $this->from);
        }

        return redirect()->intended(route('home', app()->getLocale()));
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
