<?php

namespace App\Services\Sponsorship;

use App\DAL\UserRepository;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\SharesBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\Settings\SettingService;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\BalanceOperation;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function __construct(private UserRepository $userRepository, private BalancesManager $balancesManager, private SettingService $settingService)
    {
        $settingIds = ['24', '25', '26', '27', '28', '31', '32'];
        $settingValues = $this->settingService->getIntegerValues($settingIds);
        $this->shares = $settingValues['24'];
        $this->reservation = $settingValues['25'];
        $this->amount = $settingValues['26'];
        $this->amountCash = $settingValues['27'];
        $this->amountBFS = $settingValues['28'];
        $this->saleCount = $settingValues['31'];
        $this->retardatifReservation = $settingValues['32'];
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
        $userBalancesQuery = SharesBalances::where('balance_operation_id', BalanceOperationsEnum::OLD_ID_44->value)
            ->where('beneficiary_id', $downLine->idUser)
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('created_at') . ', NOW()) < ?', [$this->retardatifReservation])
            ->orderBy(DB::raw('created_at'), "ASC")
            ->limit($this->saleCount);


        $userBalances = $userBalancesQuery->get();

        foreach ($userBalances as $userBalance) {
            $this->executeProactifSponsorship(
                $upLine->idUser, $userBalance->reference, $userBalance->value, $userBalance->unit_price, $downLine->idUser
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

    public function executeProactifSponsorship($reserve, $ref, $number_of_action, $actual_price, $resiver)
    {
        $amount = $number_of_action * $actual_price * $this->amount / 100;
        $balances = Balances::getStoredUserBalances($reserve);
        $value = intdiv($number_of_action * $this->shares, 100);
        DB::beginTransaction();
        try {
            SharesBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_52->value,
                'operator_id' => $this->isSource,
                'beneficiary_id' => $reserve,
                'reference' => $ref,
                'unit_price' => 0,
                'value' => $value,
                'description' => 'sponsorship commission from ' . getUserDisplayedName($resiver),
                'current_balance' => $balances->share_balance + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_52->value) * $value)
            ]);
            $value = $amount * $this->amountCash / 100;
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_49->value,
                'operator_id' => $this->isSource,
                'beneficiary_id' => $reserve,
                'reference' => $ref,
                'description' => 'sponsorship commission from ' . getUserDisplayedName($resiver),
                'value' => $value,
                'current_balance' => $balances->cash_balance + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_49->value) * $value)
            ]);
            $balances = Balances::getStoredUserBalances($reserve);
            BFSsBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_50->value,
                'operator_id' => $this->isSource,
                'beneficiary_id' => $reserve,
                'reference' => $ref,
                'percentage' => BFSsBalances::BFS_50,
                'description' => 'sponsorship commission from ' . getUserDisplayedName($resiver),
                'value' => $amount * $this->amountBFS / 100,
                'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_50) + BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_50->value) * $amount * $this->amountBFS / 100
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

}
