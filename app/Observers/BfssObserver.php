<?php

namespace App\Observers;

use App\Models\BFSsBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\BalanceOperation;
use Core\Models\Setting;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\Log;


class BfssObserver
{
    const MIN_BFSS_TO_GET_ACTION = 1000;

    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public function created(BFSsBalances $bFSsBalances)
    {
        $setting = Setting::WhereIn('idSETTINGS', ['22', '23'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $md = $setting[0];
        $rc = $setting[1];
        $balances = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);
        $balanceOperation = BalanceOperation::find($bFSsBalances->balance_operation_id);

        if ($bFSsBalances->percentage == "100.00" && $balanceOperation->io == 'I') {
            $this->checkSharesFromGiftedBFs($bFSsBalances);
        }

        if ($bFSsBalances->percentage == "100.00" && $balanceOperation->io == 'I') {
            $value = Balances::getDiscountEarnedFromBFS100I($bFSsBalances->value);
            DiscountBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $bFSsBalances->beneficiary_id,
                    'reference' => $bFSsBalances->reference,
                    'value' => $value,
                    'description' => number_format(100 * $value, 2, '.', '') . '%',
                    'current_balance' => $balances->discount_balance + $value
                ]
            );
        }

        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($bFSsBalances->beneficiary_id);

        $newBfssBalanceVertical = floatval($userCurrentBalancehorisontal->getBfssBalance($bFSsBalances->percentage)) + (BalanceOperation::getMultiplicator($bFSsBalances->balance_operation_id) * $bFSsBalances->value);

        $userCurrentBalancehorisontal->setBfssBalance($bFSsBalances->percentage, $newBfssBalanceVertical);

        $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $bFSsBalances->beneficiary_id)->where('balance_id', BalanceEnum::BFS)->first();

        $userCurrentBalanceVertical->update(
            [
                'current_balance' => $userCurrentBalanceVertical->current_balance + BalanceOperation::getMultiplicator($bFSsBalances->balance_operation_id) * $bFSsBalances->value,
                'previous_balance' => $userCurrentBalanceVertical->current_balance,
                'last_operation_id' => $bFSsBalances->id,
                'last_operation_value' => $bFSsBalances->value,
                'last_operation_date' => $bFSsBalances->created_at,
            ]
        );

        Log::info('BfsObserver current_balance ' . $newBfssBalanceVertical . '(Pourcentage: ' . $bFSsBalances->percentage . ') => Total Bfss: ' . $userCurrentBalanceVertical->current_balance);
    }

    public function checkSharesFromGiftedBFs(BFSsBalances $bFSsBalances)
    {
        $minBfs = getSettingIntegerParam('MIN_BFSS_TO_GET_ACTION', self::MIN_BFSS_TO_GET_ACTION);
        if ($minBfs <= $bFSsBalances->value) {
            $actualActionValue = actualActionValue(getSelledActions(true), false);
            $ref = BalancesFacade::getReference(BalanceOperationsEnum::SPONSORSHIP_COMMISSION_SHARE->value);
            $numberOfActions = intval($bFSsBalances->value / $actualActionValue);
            $balances = Balances::getStoredUserBalances(auth()->user()->idUser);
            SharesBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::SPONSORSHIP_COMMISSION_SHARE->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'unit_price' => 0,
                'description' => $numberOfActions . ' share(s) from BFS',
                'value' => $numberOfActions,
                'current_balance' => $balances->share_balance + $numberOfActions
            ]);
            Log::info('BfsObserver: ' . $numberOfActions . ' share(s) credited to user ID ' . auth()->user()->idUser . ' from gifted BFS balance of ' . $bFSsBalances->value);
        }
    }
}
