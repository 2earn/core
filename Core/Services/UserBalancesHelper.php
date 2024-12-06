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
use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\SettingsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\Amount;
use Core\Models\user_balance;
use Illuminate\Support\Facades\DB;

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
                $operationSMS = $this->balanceOperationmanager->getBalanceOperation(BalanceOperationsEnum::Achat_SMS_SMS->value);
                $Count = DB::table('user_balances')->count();
                $ref = $operationSMS->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $date = date('Y-m-d H:i:s');
                // user__balance old
                $id_balance = $this->userBalancesRepository->inserUserBalancestGetId(
                    $ref,
                    BalanceOperationsEnum::Achat_SMS_SMS->value,
                    $date,
                    '11111111',
                    $idUserUpline,
                    $operationSMS->idamounts,
                    $value
                );
                // user__balance new
                SMSBalances::addLine(
                    [
                        'balance_operation_id' =>BalanceOperationsEnum::Achat_SMS_SMS->value,
                        'operator_id' => Balances::SYSTEM_SOURCE_ID,
                        'beneficiary_id' => $idUserUpline,
                        'reference' => $ref,
                        'value' => $value,
                        'sms_price' => null,
                        'current_balance' => null
                    ]
                );


                $soldes = DB::table('user_current_balance_verticals')
                    ->where('user_id', $idUserUpline)
                    ->where('balance_id', $operationSMS->idamounts)
                    ->first();

                // CONVERTED IN BALANCES
                DB::table('sms_balances')->where('id', $id_balance)->update(['current_balance' => $soldes->solde]);
                break;
        }
    }

    /**
     * @param EventBalanceOperationEnum $event
     * @param $idUser
     * @param array|null $params
     * @return void
     * add balance operation in table user_balances according to the type event
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
                $initialDiscount = DB::table('settings')->where("ParameterName", "=", 'INITIAL_DISCOUNT')->first();
                $initialTree = DB::table('settings')->where("ParameterName", "=", 'INITIAL_TREE')->first();
                $initialChance = DB::table('settings')->where("ParameterName", "=", 'INITIAL_CHANCE')->first();

                DiscountBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_DB,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_DB->value),
                    'value' => $initialDiscount,
                    'current_balance' => $initialDiscount
                ]);
                TreeBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_TREE,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_TREE->value),
                    'value' => $initialTree,
                    'current_balance' => $initialTree
                ]);
                ChanceBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::INITIAL_CHANE,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::INITIAL_CHANE->value),
                    'value' => $initialChance,
                    'current_balance' => $initialChance
                ]);
                // UPDATE HORISANTAL AND VERTICAL BALANCES TABLES WITH OBSERVER
                break;
            case EventBalanceOperationEnum::ExchangeCashToBFS :
                if (($params) == null) dd('throw exception');
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::CASH_TO_BFS_CB->value);
                CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::CASH_TO_BFS_CB->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::From_CASH_Balance_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeBFS"]
                ]);
                break;
            case EventBalanceOperationEnum::ExchangeBFSToSMS :

                if (($params) == null) dd('throw exception');

                $ref = BalancesFacade::getReference(BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value);
//TO FILL
                $oldSMSSOLD = 0;
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                $prix_sms = $seting->IntegerValue;

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);

                SMSBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS_BALANCE_SMS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => intdiv($params["montant"], $prix_sms),
                    'sms_price' => $prix_sms,
                    'current_balance' => $params["montant"] + $oldSMSSOLD
                ]);

                break;
            case EventBalanceOperationEnum::SendSMS:
                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::ENVOYE_SMS->value)->first();
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                $prix_sms = $seting->IntegerValue;
                $date = date('Y-m-d H:i:s');
                // CONVERTED IN BALANCES
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::ENVOYE_SMS);
                // user__balance old
                $user_balance = new user_balance([
                    'ref' => $ref,
                    'idBalancesOperation' => BalanceOperationsEnum::ENVOYE_SMS->value,
                    'Date' => $date,
                    'idSource' => $idUser,
                    'idUser' => $idUser,
                    'idamount' => $BalancesOperation->amounts_id,
                    'value' => $prix_sms,
                    'WinPurchaseAmount' => "0.000",
                    'PrixUnitaire' => $prix_sms
                ]);
                $user_balance->save();
                // user__balance new
                SMSBalances::addLine(
                    [
                        'balance_operation_id' => $BalancesOperation->id,
                        'operator_id' => $idUser,
                        'beneficiary_id' => $idUser,
                        'reference' => $ref,
                        'value' => $prix_sms,
                        'sms_price' => $prix_sms,
                        'current_balance' => null
                    ]
                );
                break;
            case EventBalanceOperationEnum::SendToPublicFromCash:
                ///idSource is the sender
                /// idUser is the recipient : we will retrieve it from the table params
                if (($params) == null) dd('throw exception');
                $soldeSender = $this->balanceOperationmanager->getBalances($idUser);
                if (floatval($soldeSender->soldeCB) < floatval($params['montant'])) return;
                $soldeRecipient = $this->balanceOperationmanager->getBalances($params['recipient']);
                $newSoldeBFSRecipient = floatval($soldeRecipient->soldeBFS) + floatval($params['montant']);
                $newSoldeCashSender = floatval($soldeSender->soldeCB) - floatval($params['montant']);
                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value)->first();
                $date = date('Y-m-d H:i:s');
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value);
                // user__balance old
                $user_balance = new  user_balance([
                    'ref' => $ref,
                    'idBalancesOperation' => $BalancesOperation->id,
                    'Date' => $date,
                    'idSource' => $idUser,
                    'idUser' => $params['recipient'],
                    'idamount' => $BalancesOperation->amounts_id,
                    'value' => $params["montant"],
                    'WinPurchaseAmount' => "0.000",
                    'Balance' => $newSoldeBFSRecipient
                ]);
                $user_balance->save();
                // user__balance new
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeBFSRecipient
                ]);

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value)->first();
                // CONVERTED IN BALANCES
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value);
                // user__balance old
                $user_balance = new  user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => $BalancesOperation->id,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $newSoldeCashSender
                    ]
                );
                $user_balance->save();
                // user__balance new
                CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getRederence(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value),
                    'description' => "Transfered from " . getPhoneByUser(Auth()->user()->idUser),
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeCashSender
                ]);

                break;
            case EventBalanceOperationEnum::SendToPublicFromBFS:
                ///idSource is the sender
                /// idUser is the recipient : we will retrieve it from the table params
                if (($params) == null) dd('throw exception');
                $soldeSender = $this->balanceOperationmanager->getBalances($idUser);
                if (floatval($soldeSender->soldeBFS) < floatval($params['montant'])) return;
                $soldeRecipient = $this->balanceOperationmanager->getBalances($params['recipient']);
                $newSoldeBFSRecipient = floatval($soldeRecipient->soldeBFS) + floatval($params['montant']);
                $newSoldeCashSender = floatval($soldeSender->soldeBFS) - floatval($params['montant']);
                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value)->first();
                $date = date('Y-m-d H:i:s');
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference(BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value);
                // user__balance OLD
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $params['recipient'],
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $newSoldeBFSRecipient
                    ]
                );
                $user_balance->save();
                // user__balance new
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeBFSRecipient
                ]);

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value);
                // user__balance old
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $newSoldeCashSender
                    ]
                );
                $user_balance->save();
                // user__balance new
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeCashSender
                ]);

                break;
        }
    }
}
