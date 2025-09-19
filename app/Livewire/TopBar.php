<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\User;
use App\Services\Balances\Balances;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TopBar extends Component
{
    public $count = 0;
    public $notifications = [];
    public $unreadNotifications = [];
    public $currentRoute;
    public $userProfileImage;
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
        $this->userProfileImage = User::getUserProfileImage(auth()->user()->idUser);
    }

    public function markAsRead($idNotification, settingsManager $settingsManager)
    {
        auth()->user()->unreadNotifications->where('id', $idNotification)->first()?->markAsRead();
        $this->count = \auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->unreadNotifications()->get();
        $this->dispatch('updateNotifications', ['type' => 'warning', 'title' => "Opt", 'text' => '',]);
    }

    public function logout(settingsManager $settingsManager)
    {
        $fromSession = session('token_responce');
        $response = Http::withToken($fromSession['access_token'])->post(config('services.auth_2earn.logout'));
        Log::notice('Logout :: ' . json_encode($response));
        Log::notice('Logout :: ' . json_encode($response->body()));
        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()]);
    }

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $authUser = auth()->user();
        $user = $settingsManager->getUserById($authUser->id);
        $this->count = auth()->user()->unreadNotifications()->count();
        $this->notifications = auth()->user()->notifications()->get();
        $this->unreadNotifications = auth()->user()->unreadNotifications()->get();
        $this->locales = config('app.available_locales');
        if (!$authUser)
            dd('not found page');
        $balances = Balances::getStoredUserBalances($authUser->idUser);
        $params = [
            'cash' => !is_null($balances) ? $balances->cash_balance : 0,
            'bfs' => Balances::getTotalBfs($balances),
            'db' => !is_null($balances) ? $balances->discount_balance : 0,
            'user' => $authUser,
            'userStatus' => $user->status,
            'userRole' => $user->getRoleNames()->first(),
            'sectors' => BusinessSector::all()
        ];
        return view('livewire.top-bar', $params);
    }
}
