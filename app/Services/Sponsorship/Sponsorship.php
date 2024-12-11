<?php

namespace App\Services\Sponsorship;

use App\DAL\UserRepository;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\SharesBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
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
        $this->shares = $setting[0];
        $this->reservation = $setting[1];
        $this->amount = $setting[2];
        $this->amountCash = $setting[3];
        $this->amountBFS = $setting[4];
        $this->saleCount = $setting[5];
        $this->retardatifReservation = $setting[6];
    }

    public function checkDelayedSponsorship($upLine, $downLine): ?User
    {
        if ($downLine->idUpline == $this->isSource) {
            $this->userRepository->updateUserUpline($downLine->idUser, auth()->user()->idUser);
            return $downLine;
        }
        return NULL;
    }

    public function executeDelayedSponsorship($upLine, $downLine)
    {
        $userBalancesQuery = user_balance::where('idBalancesOperation', BalanceOperationsEnum::SELLED_SHARES->value)
            ->where('idUser', $downLine->idUser)
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()) < ?', [$this->retardatifReservation])
            ->orderBy(DB::raw('DATE'), "ASC")
            ->limit($this->saleCount);

        $userBalances = $userBalancesQuery->get();

        foreach ($userBalances as $userBalance) {
            $this->executeProactifSponsorship(
                $upLine->idUser, $userBalance->ref, $userBalance->value, $userBalance->gifted_shares, $userBalance->PU, $downLine->fullphone_number
            );
        }
    }

    public function checkProactifSponsorship($sponsor): ?User
    {

        if ($sponsor->idUpline == 0) {
              $date = new \DateTime($sponsor->reserved_at);
            $availability = $date->diff(now())->h + $date->diff(now())->i / 60 + $date->diff(now())->s / 3600;

            if ($availability < $this->reservation && $sponsor->availablity == 1) {
                $this->userRepository->updateUserUpline($sponsor->idUser, $sponsor->reserved_by);
                return $this->userRepository->getUserByIdUser($sponsor->reserved_by);
            } else {
                $this->userRepository->updateUserUpline($sponsor->idUser, $this->isSource);
            }
        } else {
        if ($sponsor->purchasesNumber < $this->saleCount && $sponsor->idUpline != $this->isSource) {
                return $this->userRepository->getUserByIdUser($sponsor->idUpline);
            }
        }
        return NULL;

    }

    public function executeProactifSponsorship($reserve, $ref, $number_of_action, $gift, $PU, $fullphone_number)
    {
        $amount = ($number_of_action + $gift) * $PU * $this->amount / 100;

        DB::beginTransaction();
        try {
            SharesBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::SPONSORSHIP_COMMISSION_SHARE->value,
            'operator_id' => $this->isSource,
            'beneficiary_id' => $reserve,
            'reference' => $ref,
            'unit_price' => 0,
            'payed' => 1,
            'value' => $number_of_action * $this->shares / 100,
            'description' => 'sponsorship commission from ' . $fullphone_number,
            'current_balance' => null// get old current balance value + > $number_of_action * $this->shares / 100
        ]);

        CashBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::SPONSORSHIP_COMMISSION_CASH->value,
            'operator_id' => $this->isSource,
            'beneficiary_id' => $reserve,
            'reference' => $ref,
            'description' => 'sponsorship commission from ' . $fullphone_number,
            'value' => $amount * $this->amountCash / 100,
            'current_balance' => $this->balancesManager->getBalances($reserve, -1)->soldeCB + $amount * $this->amountCash / 100
        ]);

        BFSsBalances::addLine([
            'balance_operation_id' => BalanceOperationsEnum::SPONSORSHIP_COMMISSION_BFS->value,
            'operator_id' => $this->isSource,
            'beneficiary_id' => $reserve,
            'reference' => $ref,
            'description' => 'sponsorship commission from ' . $fullphone_number,
            'value' => $amount * $this->amountBFS / 100,
            'current_balance' => $this->balancesManager->getBalances($reserve, -1)->soldeBFS + $amount * $this->amountBFS / 100
        ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
