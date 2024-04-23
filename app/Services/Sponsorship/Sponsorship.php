<?php

namespace App\Services\Sponsorship;

use App\DAL\UserRepository;
use App\Models\User;
use Core\Enum\AmoutEnum;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;

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

    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager)
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

    public function checkDelayedSponsorship($upLine, $downLine): ?User
    {
        if ($downLine->idUpline == 0) {
            $retardatifReservation = $this->retardatifReservation;
            $userQuery = User::where(['reserved_by' => $upLine->idUser, 'idUser' => $downLine->idUser])
                ->where(function ($query) use ($retardatifReservation) {
                    $query->orwhere('availablity', '0')
                        ->orwhere(function ($query) use ($retardatifReservation) {
                            $query->where('availablity', '1')
                                ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$retardatifReservation]);
                        });
                });

            $user = $userQuery->orderBy('reserved_at')->first();

            if ($user) {
                $this->userRepository->updateUserUpline($user, auth()->user()->id);
                return $user;
            }
        }
        return NULL;
    }

    public function testexecuteDelayedSponsorship($sponsor)
    {
        $this->executeProactifSponsorship(197604325, 100, 20, 5, '0021622950809');
    }

    public function executeDelayedSponsorship($upLine, $downLine)
    {
        $userBalancesQuery = user_balance::where('idBalancesOperation', 44)
            ->where('idUser', $downLine->idUser)
            ->where('description', 'not like', "sponsorship commission from%")
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()) < ?', [$this->retardatifReservation])
            ->orderBy(DB::raw('DATE'), "ASC")
            ->limit($this->saleCount);

        $userBalances = $userBalancesQuery->get();
        foreach ($userBalances as $userBalance) {
            $this->executeProactifSponsorship(
                $downLine->idUser, $userBalance->value, $userBalance->gifted_shares, $userBalance->PU, $this->balancesManager, $downLine->fullphone_number
            );
        }
    }

    public function checkProactifSponsorship($sponsor): ?User
    {
        if ($sponsor->idUpline == 0) {
            $date = new \DateTime($sponsor->reserved_at);
            $availablity = $date->diff(now())->format('%h');
            if ($availablity < $this->bookingHours && $sponsor->availablity == 1) {
                $this->userRepository->updateUserUpline($sponsor->id, $sponsor->reserved_by);
                return $this->userRepository->getUserByIdUser($sponsor->reserved_by);
            } else {
                $this->userRepository->updateUserUpline($sponsor->id, $this->isSource);
            }
        } else {
            if ($sponsor->purchasesNumber < $this->saleCount && $sponsor->idUpline != $this->isSource) {
                return $this->userRepository->getUserByIdUser($sponsor->idUpline);
            }
        }
        return NULL;
    }

    public function createUserBalances($ref, $idBalancesOperation, $idSource, $reserve, $idAmount, $value, $giftedShares, $PU, $winPurchaseAmount, $description, $balance): user_balance
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
        $user_balance->save();
        return $user_balance;
    }

    public function executeProactifSponsorship($reserve, $number_of_action, $gift, $PU, $fullphone_number)
    {
        $Count = DB::table('user_balances')->count();
        $ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
        $amount = ($number_of_action + $gift) * $PU * $this->amount / 100;
        $action = $this->createUserBalances(
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

        $bl = $this->createUserBalances(
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
            $this->balancesManager->getBalances(auth()->user()->idUser)->soldeCB + $amount * $this->amountCash / 100
        );

        $bfs = $this->createUserBalances(
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
            $this->balancesManager->getBalances(auth()->user()->idUser)->soldeBFS + $amount * $this->amountBFS / 100
        );
    }

}
