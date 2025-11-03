<?php

namespace App\Http\Controllers;

use App\Models\CashBalances;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Enum\BalanceOperationsEnum;
use Core\Services\BalancesManager;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class BalancesController extends Controller
{
    public function getTransfertQuery()
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', BalanceOperationsEnum::OLD_ID_42->value)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->whereNotNull('description')
            ->orderBy('created_at', 'DESC');
    }

    public function getTransfert()
    {
        return datatables($this->getTransfertQuery())->make(true);
    }

    public function addCash(Req $request, BalancesManager $balancesManager)
    {
        DB::beginTransaction();
        try {
            $old_value = Balances::getStoredUserBalances(Auth()->user()->idUser, Balances::CASH_BALANCE);
            if (intval($old_value) < intval($request->amount)) {
                throw new \Exception(Lang::get('Insuffisant cash solde'));
            }
            $ref = BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_42->value);
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_48->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => auth()->user()->idUser,
                'reference' => $ref,
                'description' => "Transfered to " . getPhoneByUser($request->input('reciver')),
                'value' => $request->input('amount'),
                'current_balance' => $balancesManager->getBalances(auth()->user()->idUser, -1)->soldeCB - $request->input('amount')
            ]);

            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_43->value,
                'operator_id' => auth()->user()->idUser,
                'beneficiary_id' => $request->input('reciver'),
                'reference' => $ref,
                'description' => "Transfered from " . getPhoneByUser(Auth()->user()->idUser),
                'value' => $request->input('amount'),
                'current_balance' => $balancesManager->getBalances($request->input('reciver'), -1)->soldeCB + $request->input('amount')
            ]);
            $message = $request->amount . ' $ ' . Lang::get('for') . ' ' . getUserDisplayedName($request->input('reciver'));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json($exception->getMessage(), 500);
        }
        return response()->json(Lang::get('Successfully runned operation') . ' ' . $message, 200);

    }
}
