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
    public $count = 0;
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;

    protected $listeners = [
        'markAsRead' => 'markAsRead',
    ];

    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->count = \auth()->user()->unreadNotifications()->count();
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $authUser = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($authUser->id);
        if (!$authUser)
            dd('not found page');
        $params = [
            'solde' => $balancesManager->getBalances($authUser->idUser, 0),
            'user' => $authUser,
            'newUreadNotificationsNumber' => \auth()->user()->unreadNotifications()->count(),
            'notifications' => $user->unreadNotifications()->get(),
            'notificationbr' => $settingsManager->getNomberNotification(),
            'userRole' => $user->getRoleNames()->first()
        ];
        return view('livewire.top-bar', $params);
    }


    public function markAsRead($idNotification, settingsManager $settingsManager)
    {
        auth()->user()->unreadNotifications->where('id', $idNotification)->first()?->markAsRead();
        $this->count = \auth()->user()->unreadNotifications()->count();
    }

    public function logout(settingsManager $settingsManager)
    {
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()])->with('FromLogOut', 'FromLogOut');
    }

}
