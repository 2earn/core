<?php

namespace App\Http\Livewire;
use Core\Models\language;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;


class TopBar extends Component
{
    protected $user_info = "";
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;

    public function mount(
        settingsManager $settingsManager,
        BalancesManager $balancesManager
    )
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
//        App::setLocale(app()->getLocale()) ;
//          dd(App::getLocale());
        $dd = Lang::get('Edit');
        //  dd($dd);
    }

    public function render(settingsManager $settingsManager)
    {

        $user = $settingsManager->getAuthUser();
        $userById = $settingsManager->getUserById($user->id);
       $userRole = $userById->getRoleNames()->first();
        $notif = $settingsManager->getNomberNotification();

        if (!$user)
            dd('not found page');
        $solde = $this->balancesManager->getBalances($user->idUser);

        return view('livewire.top-bar',
            [
                'solde' => $solde,
                'user' => $user,
                'notificationbr' => $notif,
                'userRole'=>$userRole
            ]
        );
    }

    public function logout(settingsManager $settingsManager)
    {
//        redirect()->route('home', app()->getLocale());
        $settingsManager->logoutUser();

//        dd('sd');
//        return redirect()->route('login', app()->getLocale())->with('message', Lang::get('your phone or your password is incorrect !'));

        return redirect()->route('login',['locale'=>app()->getLocale()])->with('FromLogOut', 'FromLogOut');
    }









}
