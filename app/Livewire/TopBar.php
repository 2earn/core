<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\BalancesManager;
use App\Services\settingsManager;
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
        try {
            $fromSession = session('token_responce');
            if ($fromSession && isset($fromSession['access_token'])) {
                $response = Http::withToken($fromSession['access_token'])
                    ->timeout(5)
                    ->post(config('services.auth_2earn.logout'));

                Log::notice('Logout :: ' . json_encode($response));
                Log::notice('Logout :: ' . json_encode($response->body()));
            }
        } catch (\Exception $e) {
            Log::error("Logout External API Error: " . $e->getMessage());
        }

        $settingsManager->logoutUser();
        return redirect()->route('login', ['locale' => app()->getLocale()]);
    }

    /**
     * Get validation status configuration based on user status
     *
     * @param int $status User status code
     * @return array Configuration with icon, color, title, and display flag
     */
    private function getValidationStatusConfig(int $status): array
    {
        $statusConfig = [
            1 => [
                'icon' => 'mdi mdi-22px mdi-account-alert',
                'color' => 'text-warning',
                'title' => __('National identification request in process'),
                'show' => true,
            ],
            2 => [
                'icon' => 'mdi mdi-22px mdi-account-check',
                'color' => 'text-success',
                'title' => __('National identified'),
                'show' => true,
            ],
            4 => [
                'icon' => 'mdi mdi-22px mdi-account-check',
                'color' => 'text-info',
                'title' => __('International identified'),
                'show' => true,
            ],
            5 => [
                'icon' => 'mdi mdi-22px mdi-account-alert',
                'color' => 'text-warning',
                'title' => __('International identification request in process'),
                'show' => true,
            ],
            6 => [
                'icon' => 'mdi mdi-22px mdi-account-alert',
                'color' => 'text-warning',
                'title' => __('Global identification request in process'),
                'show' => true,
            ],
        ];

        return $statusConfig[$status] ?? [
            'icon' => '',
            'color' => '',
            'title' => '',
            'show' => false,
        ];
    }

    /**
     * Get badge color class based on user status
     *
     * @param int $status User status code
     * @return string CSS class for badge color
     */
    private function getBadgeColorClass(int $status): string
    {
        return $status === 1 ? 'text-success' : 'text-muted';
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

        $userStatus = $user->status;
        $validationStatus = $this->getValidationStatusConfig($userStatus);

        $params = [
            'cash' => !is_null($balances) ? $balances->cash_balance : 0,
            'bfs' => Balances::getTotalBfs($balances),
            'db' => !is_null($balances) ? $balances->discount_balance : 0,
            'user' => $authUser,
            'userStatus' => $userStatus,
            'userRole' => $user->getRoleNames()->first(),
            'sectors' => BusinessSector::limit(6)->get(),
            'sectorsNumber' => BusinessSector::count(),
            'validationStatus' => $validationStatus,
            'badgeColorClass' => $this->getBadgeColorClass($userStatus),
        ];
        return view('livewire.top-bar', $params);
    }
}
