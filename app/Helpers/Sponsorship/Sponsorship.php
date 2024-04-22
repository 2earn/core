<?php

namespace App\Helpers\Sponsorship;

use App\DAL\UserBalancesRepository;
use App\DAL\UserRepository;
use App\Models\User;
use Core\Enum\AmoutEnum;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Core\Services\UserBalancesHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Sponsorship
{
    private $bookingHours;
    private $shares;
    private $reservation;
    private $amount;
    private $amountCash;
    private $isSource = 11111111;
    private $saleCount;
    private $retardatifReservation;

    public function __construct(private UserRepository $userRepository, BalancesManager $balancesManager)
    {
        $settingIds = ['24', '25', '26', '27', '28', '31', '32'];
        $setting = Setting::WhereIn('idSETTINGS', $settingIds)->orderBy('idSETTINGS')->pluck('IntegerValue');
        $this->bookingHours = $setting[0];
        $this->reservation = $setting[1];
        $this->amount = $setting[2];
        $this->amountCash = $setting[3];
        $this->amountBFS = $setting[4];
        $this->saleCount = $setting[5];
        $this->retardatifReservation = $setting[6];
    }

    public function checkDelayedSponsorship($sponsor): ?User
    {
        $idUpline = $nombredAchat = $maxNombredAchat = 0;
        if ($sponsor->idUpline == 0) {
            $retardatifReservation = $this->retardatifReservation;
            $user = User::where('reserved_by', $sponsor->idUser)
                ->where('availablity', '0')
                ->orwhere(function ($query) use ($retardatifReservation) {
                    $query->where('availablity', '0')
                        ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) > ?', [$retardatifReservation]);
                })
                ->orderBy('reserved_at')
                ->first();
            if ($user) {
                $this->userRepository->getUserUpline($user, auth()->user()->id);
                return $user;
            }
        }
        return NULL;
    }

    public function executeDelayedSponsorship($sponsoredUser)
    {
        $userBalances = user_balance::where('idBalancesOperation', 44)
            ->where('idUser', $sponsoredUser->idUser)
            ->whereRaw('TIMESTAMPDIFF(HOUR, "DATE", NOW()) > ?', [$this->retardatifReservation])
            ->orderBy('DATE', "ASC")
            ->limit($this->saleCount);
        foreach ($userBalances as $userBalance) {
            $this->executeDelayedSponsorship(
                $sponsoredUser->idUser, $userBalance->value, $userBalance->gifted_shares, $userBalance->PU, $this->balancesManager, $sponsoredUser->fullphone_number
            );
        }
    }

    public function checkProactifSponsorship($sponsor): ?User
    {
        if ($sponsor->idUpline == 0) {
            $user = User::where('reserved_by', $sponsor->idUser)
                ->where('availablity', '1')
                ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$this->bookingHours])
                ->orderBy('reserved_at')
                ->first();
            if ($user) {
                $this->userRepository->getUserUpline($user->id, $user->idUpline++);
                return $user;
            } else {
                $this->userRepository->getUserUpline($user->id, $this->isSource);
            }
        } else {
            $user = User::where('reserved_by', '!=', $this->isSource)
                ->where('purchasesNumber', '<=', $this->saleCount);
            return $user;
        }
        return NULL;
    }

    public function createUserBalances($ref, $idBalancesOperation, $idSource, $reserve, $idAmount, $value, $giftedShares, $PU, $winPurchaseAmount, $description, $balance): bool
    {
        $user_balance = new user_balance();
        $user_balance->ref = $ref;
        $user_balance->idBalancesOperation = $idBalancesOperation;
        $user_balance->Date = now();
        $user_balance->idSource = $idSource;
        $user_balance->idUser = $reserve;
        $user_balance->idamount = $idAmount;
        $user_balance->value = $value;
        if (!is_null($giftedShares)) {
            $user_balance->gifted_shares = $giftedShares;
        }
        $user_balance->PU = $PU;
        $user_balance->WinPurchaseAmount = $winPurchaseAmount;
        $user_balance->Description = $description;
        $user_balance->Balance = $balance;
        return $user_balance->save();
    }

    public function executeProactifSponsorship($reserve, $number_of_action, $gift, $PU, $balancesManager, $fullphone_number): bool
    {
        $Count = DB::table('user_balances')->count();
        $ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
        $amount = ($number_of_action + $gift) * $PU * $this->amount / 100;

        $this->createUserBalances(
            $ref,
            44,
            $this->isSource,
            $reserve,
            AmoutEnum::Action,
            0,
            $number_of_action * $this->shares / 100,
            0,
            "0",
            'sponsorship commission from ' . $fullphone_number,
            0
        );

        $this->createUserBalances(
            $ref,
            49,
            $this->isSource,
            $reserve,
            AmoutEnum::CASH_BALANCE,
            $amount * $this->amountCash / 100,
            null,
            0,
            "0.000",
            'sponsorship commission from ' . $fullphone_number,
            $balancesManager->getBalances(auth()->user()->idUser)->soldeCB + $amount * $this->amountCash / 100
        );

        $this->createUserBalances(
            $ref,
            50,
            $this->isSource,
            $reserve,
            AmoutEnum::BFS,
            $amount * $this->amountBFS / 100,
            null,
            0,
            "0.000",
            'sponsorship commission from ' . $fullphone_number,
            $balancesManager->getBalances(auth()->user()->idUser)->soldeBFS + $amount * $this->amountBFS / 100
        );
    }

}
