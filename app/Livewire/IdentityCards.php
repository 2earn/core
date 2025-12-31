<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Core\Enum\StatusRequest;
use Livewire\Component;

class IdentityCards extends Component
{
    public $user;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;
    public $justExpired = false;
    public $lessThanSixMonths = false;

    public function mount($userId = null)
    {
        $authUser = auth()->user();
        $theId = $userId ?? (($authUser && method_exists($authUser, 'getAttribute')) ? $authUser->idUser : null);

        if (!$theId) {
            abort(404);
        }

        $this->user = User::where('idUser', $theId)->first();

        if (!$this->user) {
            abort(404);
        }

        $this->userNationalFrontImage = User::getNationalFrontImage($theId);
        $this->userNationalBackImage = User::getNationalBackImage($theId);
        $this->userInternationalImage = User::getInternational($theId);

        if (!is_null($this->user->expiryDate)) {
            $daysNumber = getDiffOnDays($this->user->expiryDate);
            $this->lessThanSixMonths = $daysNumber < 180;
            $this->justExpired = $daysNumber < 1;
        }
    }

    public function render()
    {
        return view('livewire.identity-cards');
    }
}
