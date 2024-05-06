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

    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function render(settingsManager $settingsManager)
    {
        $user = $settingsManager->getAuthUser();
        $userById = $settingsManager->getUserById($user->id);
        if (!$user)
            dd('not found page');
        $params = [
            'solde' => $this->balancesManager->getBalances($user->idUser, 0),
            'user' => $user,
            'notificationbr' => $settingsManager->getNomberNotification(),
            'userRole' => $userById->getRoleNames()->first()
        ];
        return view('livewire.top-bar', $params);
    }

    public function logout(settingsManager $settingsManager)
    {
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()])->with('FromLogOut', 'FromLogOut');
    }
}
