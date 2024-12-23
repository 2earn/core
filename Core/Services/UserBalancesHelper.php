<?php

namespace Core\Services;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\ChanceBalances;
use App\Models\DiscountBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Enum\ActionEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\SettingsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class  UserBalancesHelper
{
    private IUserBalancesRepository $userBalancesRepository;
    private BalancesManager $balanceOperationmanager;

    public function __construct(IUserBalancesRepository $userBalancesRepository, BalancesManager $balanceOperationmanager,)
    {
        $this->userBalancesRepository = $userBalancesRepository;
        $this->balanceOperationmanager = $balanceOperationmanager;

    }

    public function RegistreUserbalances(ActionEnum $actionType, $idUserUpline, $value)
    {
        switch ($actionType) {
            case ActionEnum::Signup :
                $soldesLine = DB::table('user_current_balance_horisentals')->where('user_id', $idUserUpline)->first();
                SMSBalances::addLine(
                    [
                        'balance_operation_id' =>BalanceOperationsEnum::Achat_SMS_SMS->value,
                        'operator_id' => Balances::SYSTEM_SOURCE_ID,
                        'beneficiary_id' => $idUserUpline,
                        'reference' => Balances::getReference(BalanceOperationsEnum::Achat_SMS_SMS->value),
                        'description' => BalanceOperationsEnum::Achat_SMS_SMS->name,
                        'value' => $value,
                        'sms_price' => null,
                        'current_balance' => $soldesLine->sms_balance
                    ]
                );
                break;
        }
    }

    /**
     * @param EventBalanceOperationEnum $event
     * @param $idUser
     * @param array|null $params
     * @return void
     * add balance operation in table user _balances according to the type event
     * 1 - type event Signup :
     * 11 - insert when balance operation designation id SI
     * 12 - insert when  balance operation is BY_REGISTERING_TREE
     * 13 - insert when  balance operation isBY_REGISTERING_DB
     * 14 - insert in table "usercurrentbalances" from all amounts
     * 2- type event exchangeCachToBFS
     * 21- .....
     * ToDo : get value to insert from parameter
     * ToDo : implement function to get Balance operation by BalanceOperationEnum
     * ToDo : implement function to get setting by SettingsEnum
     * ToDo : implement function to generate reference
     */
    public function AddBalanceByEvent(EventBalanceOperationEnum $event, $idUser, array $params = null)
    {
        switch ($event) {
            case EventBalanceOperationEnum::Signup :
                $initialDiscount = DB::table('settings')->where("ParameterName", "=", 'INITIAL_DISCOUNT')->pluck('IntegerValue');
                $initialTree = DB::table('settings')->where("ParameterName", "=", 'INITIAL_TREE')->pluck('IntegerValue');
                $initialChance = DB::table('settings')->where("ParameterName", "=", 'INITIAL_CHANCE')->pluck('IntegerValue');

                DB::beginTransaction();
                try {
                DiscountBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_DB->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_DB->value),
                    'description' => $initialDiscount . ' $ as welcome gift',
                    'value' => $initialDiscount,
                    'current_balance' => $initialDiscount
                ]);
                TreeBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_TREE->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_TREE->value),
                    'description' =>BalanceOperationsEnum::BY_REGISTERING_TREE->name,
                    'value' => $initialTree . '$ as welcome gift',
                    'current_balance' => $initialTree
                ]);
                ChanceBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::INITIAL_CHANE->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::INITIAL_CHANE->value),
                    'description' =>  BalanceOperationsEnum::INITIAL_CHANE->name,
                    'value' => $initialChance . ' $ as welcome gift',
                    'current_balance' => $initialChance
                ]);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                }
                break;
            case EventBalanceOperationEnum::ExchangeCashToBFS :
                if (($params) == null) dd('throw exception');
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::CASH_TO_BFS_CB->value);
                DB::beginTransaction();
                try {
                    CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::CASH_TO_BFS_CB->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                        'description' => $params["montant"] . ' Transfered to my BFS',
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);
                    $balances = Balances::getStoredUserBalances($idUser);;

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::From_CASH_Balance_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'percentage' => BFSsBalances::BFS_100,
                    'description' => $params["montant"] . ' Transfered from my CB',
                    'value' => $params["montant"],
                    'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_100) + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::From_CASH_Balance_BFS->value) * $params["montant"])
                ]);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                }
                break;
            case EventBalanceOperationEnum::ExchangeBFSToSMS :

                if (($params) == null) dd('throw exception');

                DB::beginTransaction();
                try {
                    $ref = BalancesFacade::getReference(BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value);
                    $oldSMSSOLD = Balances::getStoredUserBalances($idUser, "sms_balance");
                    $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                    $prix_sms = $seting->DecimalValue ?? 1.5;
                    $balances = Balances::getStoredUserBalances($idUser);;

                    BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                        'percentage' => BFSsBalances::BFS_100,
                        'description' => 'perchase of ' . $params["montant"] . ' SMS',
                    'value' => $params["montant"],
                        'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_100) + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value) * $params["montant"])
                ]);
                    $value = intdiv($params["montant"], $prix_sms);
                SMSBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS_BALANCE_SMS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'perchase of ' . $value . ' SMS',
                    'value' => $value,
                    'sms_price' => $prix_sms,
                    'current_balance' => $oldSMSSOLD + $value
                ]);

                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                }
                break;
            case EventBalanceOperationEnum::SendSMS:
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::Achat_SMS_SMS->value);
                $oldSMSSOLD = Balances::getStoredUserBalances($idUser, "sms_balance");
                SMSBalances::addLine(
                    [
                        'balance_operation_id' => BalanceOperationsEnum::Achat_SMS_SMS->value,
                        'operator_id' => $idUser,
                        'beneficiary_id' => $idUser,
                        'reference' => $ref,
                        'description' =>BalanceOperationsEnum::Achat_SMS_SMS->name,
                        'value' => 1,
                        'current_balance' => $oldSMSSOLD--
                    ]
                );
                break;
            case EventBalanceOperationEnum::SendToPublicFromCash:
                if (($params) == null) dd('throw exception');
                $soldeSender = $this->balanceOperationmanager->getBalances($idUser);
                if (floatval($soldeSender->soldeCB) < floatval($params['montant'])) return;
                $soldeRecipient = $this->balanceOperationmanager->getBalances($params['recipient']);
                $newSoldeBFSRecipient = floatval($soldeRecipient->soldeBFS) + floatval($params['montant']);
                $newSoldeCashSender = floatval($soldeSender->soldeCB) - floatval($params['montant']);

                DB::beginTransaction();
                try {
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value);

                CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->name,
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeCashSender
                ]);

                    $balances = Balances::getStoredUserBalances($params['recipient']);
                    BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'reference' => $ref,
                        'percentage' => BFSsBalances::BFS_100,
                    'description' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->name,
                    'value' => $params["montant"],
                        'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_100) + BalanceOperation::getMultiplicator(BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value) * $newSoldeBFSRecipient
                ]);

                DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                }
                break;
            case EventBalanceOperationEnum::SendToPublicFromBFS:
                if (($params) == null) dd('throw exception');
                $soldeSender = $this->balanceOperationmanager->getBalances($idUser);
                if (floatval($soldeSender->soldeBFS) < floatval($params['montant'])) return;
                $soldeRecipient = $this->balanceOperationmanager->getBalances($params['recipient']);
                $newSoldeBFSRecipient = floatval($soldeRecipient->soldeBFS) + floatval($params['montant']);
                $newSoldeCashSender = floatval($soldeSender->soldeBFS) - floatval($params['montant']);

                DB::beginTransaction();
                try {
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value);
                    $balances = Balances::getStoredUserBalances($idUser);

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'percentage' => BFSsBalances::BFS_100,
                    'description' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->name,
                    'value' => $params["montant"],
                    'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_100) + BalanceOperation::getMultiplicator(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value) * $newSoldeCashSender
                ]);
                    $balances = Balances::getStoredUserBalances($params['recipient']);
                    BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'percentage' => BFSsBalances::BFS_100,
                    'reference' => $ref,
                    'description' =>  BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->name,
                    'value' => $params["montant"],
                    'current_balance' => $balances->getBfssBalance(BFSsBalances::BFS_100) + BalanceOperation::getMultiplicator(BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value) * $newSoldeBFSRecipient
                ]);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                }
                break;
        }
    }
}
