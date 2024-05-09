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
    protected $notifications = [];
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
        $authUser = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($authUser->id);
        if (!$authUser)
            dd('not found page');
        $params = [
            'solde' => $this->balancesManager->getBalances($authUser->idUser, 0),
            'user' => $authUser,
            'notifications' => $user->notifications,
            'notificationbr' => $settingsManager->getNomberNotification(),
            'userRole' => $user->getRoleNames()->first()
        ];
        return view('livewire.top-bar', $params);
    }

    public function logout(settingsManager $settingsManager)
    {
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()])->with('FromLogOut', 'FromLogOut');
    }
}
