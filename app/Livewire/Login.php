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

    public function mount(Request $request)
    {
        $this->from = $request->query->get('form');
        $this->expireAt = getSettingIntegerParam('EXPIRE_AT', 30);
    }

    public function login($number, $code, $pass, $iso, settingsManager $settingsManager, Request $request)
    {

        if ($number == "" || $code == "" || $pass == "" || $iso == "" || strlen($iso) > 2) {
            return redirect()->route('login', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Your phone number or password is incorrect!'));
        }

        $settingsManager->loginUser(str_replace(' ', '', $number), $code, false, $pass, $iso);

        try {
            if (!is_null($this->from)) {
                Log::info('Inscription from Site 2earn :: code:' . $code . ' number: ' . $number);
                redirect()->intended(route('home', app()->getLocale()))->with('from', $this->from);
            }
            $this->redirectRoute('home', app()->getLocale());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
