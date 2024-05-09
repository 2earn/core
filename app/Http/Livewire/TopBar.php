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
    public $unreadNotificationsNumber = 0;
    public $newUreadNotificationsNumber = 0;
    protected $user_info = "";
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;

    protected $listeners = [
        'markAsRead' => 'markAsRead',
    ];

    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->updateNotifications();
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $authUser = $settingsManager->getAuthUser();
        $user = $settingsManager->getUserById($authUser->id);
        $this->updateNotifications();
        if (!$authUser)
            dd('not found page');
        $params = [
            'solde' => $balancesManager->getBalances($authUser->idUser, 0),
            'user' => $authUser,
            'notifications' => $user->unreadNotifications()->get(),
            'notificationbr' => $settingsManager->getNomberNotification(),
            'userRole' => $user->getRoleNames()->first()
        ];
        return view('livewire.top-bar', $params);
    }

    public function updateNotifications()
    {
        $this->unreadNotificationsNumber = \auth()->user()->notifications()->count();
        $this->newUreadNotificationsNumber = \auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead($idNotification, settingsManager $settingsManager)
    {
        auth()->user()->unreadNotifications->where('id', $idNotification)->first()?->markAsRead();
        $this->updateNotifications();
        $params = [
            'idNotification' => $idNotification,
            'unreadedNotificationNumber' => \auth()->user()->notifications()->count(),
            'unreadedNotificationNumber' => \auth()->user()->unreadNotifications()->count()
        ];
        $this->dispatchBrowserEvent('markAsRead', $params);
    }

    public function logout(settingsManager $settingsManager)
    {
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()])->with('FromLogOut', 'FromLogOut');
    }

}
