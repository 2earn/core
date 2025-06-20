<?php

namespace App\Livewire;

use App\Http\Traits\earnTrait;
use App\Models\MettaUser;
use App\Models\User;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use App\Notifications\contact_registred;
use Core\Enum\BalanceEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\CommandeServiceManager;
use Core\Services\settingsManager;
use Core\Services\UserBalancesHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class CheckOptCode extends Component
{
    use earnTrait;

    const MAX_ATTEMPTS = 3;
    const COOLDOWN_PERIOD = 3000;
    const RATE_KEY = 'opt-login-attempts-';

    public $code;
    public $idUser = 0;
    public $ccode;
    public $numPhone;
    public $numHelpPhone = null;

    protected $rules = [
        'code' => 'required|numeric|min:4|max:4'
    ];

    public int $expireAt;
    public int $maxAttempts;

    protected $messages = [
        'code.required' => 'The code cannot be empty.',
        'code.numeric' => 'The code format is not valid.',
        'code.min' => 'The code format is not valid.',
        'code.max' => 'The code format is not valid.',
    ];

    public function mount($iduser, $ccode, $numTel)
    {
        $this->idUser = $iduser;
        $this->ccode = $ccode;
        $this->numPhone = $numTel;

        $param = DB::table('settings')->where("ParameterName", "=", "WHATSAPP_HELP_NUMBER")->first();

        if (!is_null($param)) {
            $this->numHelpPhone = $param->StringValue;
        }
        $this->expireAt = getSettingIntegerParam('EXPIRE_AT', 30);
        $this->maxAttempts = getSettingIntegerParam('MAX_ATTEMPT', self::MAX_ATTEMPTS);
    }

    public function render()
    {
        return view('livewire.check-opt-code')->extends('layouts.master-without-nav')->section('content');
    }

    /**
     * @param settingsManager $settingsManager
     * @param CommandeServiceManager $commandeServiceManager
     * @param UserBalancesHelper $userBalancesHelper
     * @return RedirectResponse
     * 1 - CHECK validation code is required : return with error
     * 2 - check length code is 4 character : return with error
     * 3 - check expiration code : return ith error
     * 4 - check user not verified : return with error
     * 5 - generate a new password for  user
     * 6 - change status user to 0
     * 7 - assign role number 4 to the user
     * 8 - notify user with sms : send the new password
     * 9 - if the user is invited  : send notification to upline user
     * 10 - save operations of userBalance
     *  ToDo
     */
    public function initUserCurrentBalance($idUser)
    {
        $user = User::where('idUser', $idUser)->first();
        UserCurrentBalanceHorisontal::create([
            'user_id' => $idUser,
            'user_id_auto' => $user->id,
            'cash_balance' => 0,
            'bfss_balance' => [],
            'sms_balance' => 0,
            'discount_balance' => 0,
            'tree_balance' => 0,
            'share_balance' => 0,
            'chances_balance' => [],
        ]);
        foreach (BalanceEnum::cases() as $case) {
            UserCurrentBalanceVertical::create([
                'user_id' => $idUser,
                'user_id_auto' => $user->id,
                'balance_id' => $case->value,
                'current_balance' => 0,
                'last_operation_id' => 0,
                'last_operation_date' => 0,
                'last_operation_value' => 0,
            ]);
        }

    }

    public function verifCodeOpt(settingsManager $settingsManager, CommandeServiceManager $commandeServiceManager, UserBalancesHelper $userBalancesHelper)
    {
        $key = self::RATE_KEY . $this->numPhone;
        if (RateLimiter::tooManyAttempts($key,  $this->maxAttempts)) {
            Cache::put('blocked-user-' . $this->numPhone, true, now()->addMinutes($this->expireAt));
            return redirect()->route("forget_password", app()->getLocale())
                ->with('danger', Lang::get('Too many attempts! Please try again later.'));
        }
        RateLimiter::hit($key, self::COOLDOWN_PERIOD);
        $user = $settingsManager->getUsers()->where('idUser', Crypt::decryptString($this->idUser))->first();

        if (!empty($this->getErrorBag()->getMessages())) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('danger', Lang::get('Invalid OPT code.') . json_encode($this->getErrorBag()->getMessages()));
        }
        if (substr($user->OptActivation, 0, 4) != ($this->code)) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('danger', Lang::get('Invalid OPT code'));
        }
        $date = date('Y-m-d H:i:s');
        if (abs(strtotime($date) - strtotime($user->OptActivation_at)) / 60 > 1500) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('danger', Lang::get('OPT code expired'));
        }
        $user = $settingsManager->getUserById($user->id);
        if ($user->status != StatusRequest::Registred->value) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('danger', Lang::get('User already verified'));
        }
        $userUpline = $settingsManager->checkUserInvited($user);
        $password = $this->randomNewPassword(8);
        $user->password = Hash::make($password);
        $user->pass = $password;
        $user->status = StatusRequest::OptValidated;
        if ($commandeServiceManager->saveUser($user)) {
            $settingsManager->NotifyUser($user->id, TypeEventNotificationEnum::Password, ['msg' => $password, 'type' => TypeNotificationEnum::SMS]);
            $user->assignRole(4);
            if ($userUpline) {
                $userUpline->notify(new contact_registred($user->fullphone_number));
                $settingsManager->NotifyUser($userUpline->id, TypeEventNotificationEnum::ToUpline, ['msg' => $user->fullphone_number, 'toMail' => "khan_josef@hotmail.com", 'emailTitle' => "reg Title"]);
            }
            $this->initUserCurrentBalance($user->idUser);
            $userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::Signup, $user->idUser);
        }
        RateLimiter::clear(self::RATE_KEY . $this->numPhone);

        return redirect()->route('login', app()->getLocale())->with('success', Lang::get('User registered successfully, you can login now'));
    }
}
