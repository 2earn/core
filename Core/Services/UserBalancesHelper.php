<?php

namespace Core\Services;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SMSBalances;
use App\Services\Balances\Balances;
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
                $operationSMS = $this->balanceOperationmanager->getBalanceOperation(BalanceOperationsEnum::Achat_SMS_SMS);
                $Count = DB::table('user_balances')->count();
                $ref = $operationSMS->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $date = date('Y-m-d H:i:s');
                $id_balance = $this->userBalancesRepository->inserUserBalancestGetId(
                    $ref,
                    BalanceOperationsEnum::Achat_SMS_SMS,
                    $date,
                    '11111111',
                    $idUserUpline,
                    $operationSMS->idamounts,
                    $value
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
     * 12 - insert when  balance operation is By_registering_TREE
     * 13 - insert when  balance operation isBy_registering_DB
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
                $SIOperation = $this->balanceOperationmanager->getAllBalanceOperation()->where("Designation", "SI");
                $date = date('Y-m-d H:i:s');
                foreach ($SIOperation as $SI) {
                    // CONVERTED IN BALANCES
                    $ref =  BalancesFacade::getReference($SI->idBalanceOperations);
                    if ($SI->idamounts == BalanceEnum::CASH->value) {
                        // user__balance old
                        $user_balance = new user_balance([
                            'ref' => $ref,
                            'idBalancesOperation' => $SI->idBalanceOperations,
                            'Date' => $date,
                            'idSource' => '11111111',
                            'idUser' => $idUser,
                            'idamount' => $SI->idamounts,
                            'value' => 0000.000,
                            'WinPurchaseAmount' => "0.000",
                            'Balance' => 0000.000
                        ]);
                        $user_balance->save();
                        // user__balance new
                        CashBalances::addLine([
                            'item_id' => null,
                            'deal_id' => null,
                            'order_id' => null,
                            'platform_id' => 1,
                            'order_detail_id' => null,
                            'balance_operation_id' => $SI->idBalanceOperations,
                            'operator_id' => Balances::SYSTEM_SOURCE_ID,
                            'beneficiary_id' => $idUser,
                            'reference' => $ref,
                            'value' => 0000.000,
                            'current_balance' => 0000.000
                        ]);
                    } else {
                        // user__balance old
                        $user_balance = new user_balance(
                            [
                                'ref' => $ref,
                                'idBalancesOperation' => $SI->idBalanceOperations,
                                'Date' => $date,
                                'idSource' => '11111111',
                                'idUser' => $idUser,
                                'idamount' => $SI->idamounts,
                                'value' => 0.000,
                                'WinPurchaseAmount' => "0.000",
                                'Balance' => 0.000
                            ]);
                        $user_balance->save();

                        // user__balance new
                        if ($SI->idamounts == BalanceEnum::BFS->value) {
                            BFSsBalances::addLine([
                                'item_id' => null,
                                'deal_id' => null,
                                'order_id' => null,
                                'platform_id' => 1,
                                'order_detail_id' => null,
                                'balance_operation_id' => $SI->idBalanceOperations,
                                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                                'beneficiary_id' => $idUser,
                                'reference' => $ref,
                                'value' => 0000.000,
                                'current_balance' => 0000.000
                            ]);
                        } elseif ($SI->idamounts == BalanceEnum::DB->value) {
                            DiscountBalances::addLine(
                                [
                                    'item_id' => null,
                                    'deal_id' => null,
                                    'order_id' => null,
                                    'platform_id' => 1,
                                    'order_detail_id' => null,
                                    'balance_operation_id' => $SI->idBalanceOperations,
                                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                                    'beneficiary_id' => $idUser,
                                    'reference' => $ref,
                                    'value' => 0000.000,
                                    'current_balance' => 0000.000]
                            );
                        }
                    }
                }
                $BalancesOperation = DB::table('balance_operations')->where("id", "=", BalanceOperationsEnum::By_registering_TREE)->first();
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::discount_By_registering->value)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);

                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::By_registering_TREE,
                        'Date' => $date,
                        'idSource' => '11111111',
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $seting->IntegerValue,
                        'WinPurchaseAmount' => "0.000"
                    ]
                );
                $user_balance->save();
                $BalancesOperation = DB::table('balance_operations')->where("id", "=", BalanceOperationsEnum::By_registering_DB)->first();
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::token_By_registering->value)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::By_registering_DB,
                        'Date' => $date, 'idSource' => '11111111',
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $seting->IntegerValue,
                        'WinPurchaseAmount' => "0.000"
                    ]
                );
                $user_balance->save();
                //insert in table "usercurrentbalances"
                $amounts = Amount::all();
                foreach ($amounts as $amount) {
                    if ($amount->idamounts == BalanceEnum::CASH->value) {
                        DB::table('usercurrentbalances')->insert([
                            'idUser' => $idUser,
                            'idamounts' => $amount->idamounts,
                            'value' => 0.000,
                        ]);
                    } else {
                        DB::table('usercurrentbalances')->insert([
                            'idUser' => $idUser,
                            'idamounts' => $amount->idamounts,
                            'value' => 0.000,
                        ]);
                    }
                }
                break;
            case EventBalanceOperationEnum::ExchangeCashToBFS :
                if (($params) == null) dd('throw exception');
                $BalancesOperation = DB::table('balance_operations')->where("id",
                    BalanceOperationsEnum::CASH_TO_BFS_CB)->first();

                $date = date('Y-m-d H:i:s');

                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                // user__balance old

                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::CASH_TO_BFS_CB,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $params["newSoldeCashBalance"]
                    ]
                );
                $user_balance->save();

                // user__balance new
                CashBalances::addLine([
                    'item_id' => null,
                    'deal_id' => null,
                    'order_id' => null,
                    'platform_id' => 1,
                    'order_detail_id' => null,
                    'balance_operation_id' => 48,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::From_CASH_Balance_BFS)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                // user__balance old
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::From_CASH_Balance_BFS,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $params["newSoldeBFS"],
                        'PrixUnitaire' => 1
                    ]
                );
                $user_balance->save();
                // user__balance new
                BFSsBalances::addLine([
                    'item_id' => null,
                    'deal_id' => null,
                    'order_id' => null,
                    'platform_id' => 1,
                    'order_detail_id' => null,
                    'balance_operation_id' => BalanceOperationsEnum::From_CASH_Balance_BFS,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeBFS"]
                ]);
                break;
            case EventBalanceOperationEnum::ExchangeBFSToSMS :

                if (($params) == null) dd('throw exception');

                $BalancesOperation = DB::table('balance_operations')->where("id",
                    BalanceOperationsEnum::BFS_TO_SMSn_BFS)->first();
                $date = date('Y-m-d H:i:s');
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                // user__balance old
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::BFS_TO_SMSn_BFS,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $params["newSoldeCashBalance"]
                    ]);
                $user_balance->save();
                // user__balance new
                BFSsBalances::addLine([
                    'item_id' => null,
                    'deal_id' => null,
                    'order_id' => null,
                    'platform_id' => 1,
                    'order_detail_id' => null,
                    'balance_operation_id' => BalanceOperationsEnum::BFS_TO_SMSn_BFS,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::From_BFS_Balance_SMS)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                // user__balance old
                $user_balance = new user_balance();
                $user_balance->save(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::From_BFS_Balance_SMS,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->amounts_id,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $params["newSoldeBFS"],
                        'PrixUnitaire' => $params["PrixSms"]
                    ]);
                // user__balance new
                SMSBalances::addLine(
                    [
                        'item_id' => null,
                        'deal_id' => null,
                        'order_id' => null,
                        'platform_id' => 1,
                        'order_detail_id' => null,
                        'balance_operation_id' => BalanceOperationsEnum::From_BFS_Balance_SMS,
                        'operator_id' => $idUser,
                        'beneficiary_id' => $idUser,
                        'reference' => $ref,
                        'value' => $params["montant"],
                        'sms_price' => $params["PrixSms"],
                        'current_balance' => $params["newSoldeBFS"]
                    ]
                );
                break;
            case EventBalanceOperationEnum::SendSMS:
                $BalancesOperation = DB::table('balance_operations')->where("id",
                    BalanceOperationsEnum::EnvoyeSMS)->first();
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                $prix_sms = $seting->IntegerValue;
                $date = date('Y-m-d H:i:s');
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                $user_balance = new user_balance([
                    'ref' => $ref,
                    'idBalancesOperation' => $BalancesOperation->id,
                    'Date' => $date,
                    'idSource' => $idUser,
                    'idUser' => $idUser,
                    'idamount' => $BalancesOperation->amounts_id,
                    'value' => $prix_sms,
                    'WinPurchaseAmount' => "0.000",
                    'PrixUnitaire' => $prix_sms
                ]);
                $user_balance->save();

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
                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::From_public_User_BFS)->first();
                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->id . date('ymd') . substr((10000 + $Count + 1), 1, 4);
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

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::to_Other_Users_public_CB)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
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
                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::From_public_User_BFS)->first();
                $date = date('Y-m-d H:i:s');
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                $user_balance = new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => $BalancesOperation->id,
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

                $BalancesOperation = DB::table('balance_operations')->where("id", BalanceOperationsEnum::to_Other_Users_public_BFS)->first();
                // CONVERTED IN BALANCES
                $ref =  BalancesFacade::getReference($BalancesOperation->id);
                $user_balance = new user_balance(
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
                break;
        }
    }
}
