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
    const BLOCKED_KEY = 'blocked-user-';

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
        $blockedUntil = Cache::get(self::BLOCKED_KEY . $number);

        if ($blockedUntil && now()->lessThan($blockedUntil)) {
            Log::warning("Blocked login attempt: $number (Blocked until: $blockedUntil)");
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Too many login attempts. Please try again later.'));
        }

        if (RateLimiter::tooManyAttempts(self::RATE_KEY . $number, self::MAX_ATTEMPTS)) {
            $expiresAt = now()->addMinutes(10);
            Cache::put(self::BLOCKED_KEY . $number, $expiresAt, $expiresAt);
            Log::warning("User $number blocked until $expiresAt due to excessive failed attempts.");
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Too many failed attempts! You are temporarily blocked.'));
        }

        if ($number == "" || $code == "" || $pass == "" || $iso == "" || strlen($iso) > 2) {
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Your phone number or password is incorrect!'));
        }

        $user = $settingsManager->loginUser(str_replace(' ', '', $number), $code, false, $pass, $iso);
        if (!$user) {
            RateLimiter::hit(self::RATE_KEY . $number, 60);
            Log::info("Failed login attempt: $number (Attempts left: " . RateLimiter::remaining(self::RATE_KEY . $number, self::MAX_ATTEMPTS) . ")");
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Your phone or password is incorrect!'));
        }

        RateLimiter::clear(self::RATE_KEY . $number);
        Cache::forget(self::BLOCKED_KEY . $number);
        Log::info("Successful login: $number");

        if (!is_null($this->from)) {
            Log::info('Inscription from Site 2earn :: code:' . $code . ' number: ' . $number);
             redirect()->intended(route('home', app()->getLocale()))->with('from', $this->from);
        }

         redirect()->intended(route('home', app()->getLocale()));
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
