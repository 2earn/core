<?php

namespace App\Http\Livewire;

use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Livewire\Component;

class TopBar extends Component
{
    public $count = 0;
    public $notifications = [];
    public $currentRoute;
    public $locales;
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;

    protected $listeners = [
        'markAsRead' => 'markAsRead',
    ];

    public function boot()
    {
        $this->locales = config('app.available_locales');
    }

    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $authUser = auth()->user();
        $user = $settingsManager->getUserById($authUser->id);
        $this->count = auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->unreadNotifications()->get();
        $this->locales = config('app.available_locales');
        if (!$authUser)
            dd('not found page');
        $params = [
            'solde' => $balancesManager->getBalances($authUser->idUser, 2),
            'user' => $authUser,
            'userStatus' => $user->status,
            'userRole' => $user->getRoleNames()->first()
        ];
        return view('livewire.top-bar', $params);
    }


    public function markAsRead($idNotification, settingsManager $settingsManager)
    {
        auth()->user()->unreadNotifications->where('id', $idNotification)->first()?->markAsRead();
        $this->count = \auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->unreadNotifications()->get();
        $this->dispatchBrowserEvent('updateNotifications', ['type' => 'warning', 'title' => "Opt", 'text' => '',]);
    }

    public function logout(settingsManager $settingsManager)
    {
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()]);
    }
}
