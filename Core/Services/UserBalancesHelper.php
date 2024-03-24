<?php

namespace Core\Services;

use Core\Enum\ActionEnum;
use Core\Enum\AmoutEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\SettingsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\Amount;
use Core\Models\history;
use Core\Models\user_balance;
use Illuminate\Support\Facades\DB;

class  UserBalancesHelper
{
    private IUserBalancesRepository $userBalancesRepository;
    private BalancesManager $balanceOperationmanager;

    public function __construct(
        IUserBalancesRepository $userBalancesRepository,
        BalancesManager         $balanceOperationmanager,


    )
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
                $soldes = DB::table('calculated_userbalances')
                    ->where('idUser', $idUserUpline)
                    ->where('idamounts', $operationSMS->idamounts)
                    ->first();

                DB::table('user_balances')->where('id', $id_balance)
                    ->update(['Balance' => $soldes->solde]);
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
                    $Count = DB::table('user_balances')->count();
                    $ref = $SI->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                    if ($SI->idamounts == AmoutEnum::CASH_BALANCE->value) {

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
//                        DB::table('user_balances')->insert(
//                            ['ref' => $ref, 'idBalancesOperation' => $SI->idBalanceOperations,
//                                'Date' => $date, 'idSource' => '11111111', 'idUser' => $idUser, 'idamount' => $SI->idamounts,
//                                'value' => 0000.000, 'WinPurchaseAmount' => "0.000", 'Balance' => 0000.000]
//                        );
                    } else {

                        $user_balance = new user_balance(['ref' => $ref, 'idBalancesOperation' => $SI->idBalanceOperations, 'Date' => $date,
                            'idSource' => '11111111', 'idUser' => $idUser, 'idamount' => $SI->idamounts, 'value' => 0.000, 'WinPurchaseAmount' => "0.000", 'Balance' => 0.000]);


                        $user_balance->save();
//                        DB::table('user_balances')
//                            ->insert(['ref' => $ref, 'idBalancesOperation' => $SI->idBalanceOperations, 'Date' => $date,
//                                'idSource' => '11111111', 'idUser' => $idUser, 'idamount' => $SI->idamounts, 'value' => 0.000, 'WinPurchaseAmount' => "0.000", 'Balance' => 0.000]);
//                    }
                    }
                }
                    $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", "=", BalanceOperationsEnum::By_registering_TREE)->first();
                    $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::discount_By_registering->value)->first();
                    $Count = DB::table('user_balances')->count();
                    $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);

                    $user_balance=new user_balance(
                        [
                            'ref' => $ref,
                            'idBalancesOperation' => BalanceOperationsEnum::By_registering_TREE,
                            'Date' => $date,
                            'idSource' => '11111111',
                            'idUser' => $idUser,
                            'idamount' => $BalancesOperation->idamounts,
                            'value' => $seting->IntegerValue,
                            'WinPurchaseAmount' => "0.000"
                        ]
                    ) ;
                    $user_balance->save() ;
//                    DB::table('user_balances')->insert([
//                        'ref' => $ref,
//                        'idBalancesOperation' => BalanceOperationsEnum::By_registering_TREE,
//                        'Date' => $date,
//                        'idSource' => '11111111',
//                        'idUser' => $idUser,
//                        'idamount' => $BalancesOperation->idamounts,
//                        'value' => $seting->IntegerValue,
//                        'WinPurchaseAmount' => "0.000"
//                    ]);
                    $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", "=", BalanceOperationsEnum::By_registering_DB)->first();
                    $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::token_By_registering->value)->first();
                    $Count = DB::table('user_balances')->count();
                    $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                    $user_balance = new user_balance(
                        [
                            'ref' => $ref,
                            'idBalancesOperation' => BalanceOperationsEnum::By_registering_DB,
                            'Date' => $date, 'idSource' => '11111111',
                            'idUser' => $idUser,
                            'idamount' => $BalancesOperation->idamounts,
                            'value' => $seting->IntegerValue,
                            'WinPurchaseAmount' => "0.000"
                        ]
                    );
                    $user_balance->save();
//                DB::table('user_balances')
//                    ->insert([
//                        'ref' => $ref,
//                        'idBalancesOperation' => BalanceOperationsEnum::By_registering_DB,
//                        'Date' => $date, 'idSource' => '11111111',
//                        'idUser' => $idUser,
//                        'idamount' => $BalancesOperation->idamounts,
//                        'value' => $seting->IntegerValue,
//                        'WinPurchaseAmount' => "0.000"
//                    ]);
                    //insert in table "usercurrentbalances"
                    $amounts = Amount::all();
                    foreach ($amounts as $amount) {
                        if ($amount->idamounts == AmoutEnum::CASH_BALANCE->value) {
                            DB::table('usercurrentbalances')->insert([
                                'idUser' => $idUser,
                                'idamounts' => $amount->idamounts,
                                'value' => 0.000,
                            ]);
                        }
                        else {
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
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations",
                    BalanceOperationsEnum::CASH_TO_BFS_CB)->first();

                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance=new user_balance(
                    ['ref' => $ref,
                        'idBalancesOperation' => BalanceOperationsEnum::CASH_TO_BFS_CB,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->idamounts,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000", 'Balance' => $params["newSoldeCashBalance"]]
                ) ;
                $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId(
//                    ['ref' => $ref,
//                        'idBalancesOperation' => BalanceOperationsEnum::CASH_TO_BFS_CB,
//                        'Date' => $date,
//                        'idSource' => $idUser,
//                        'idUser' => $idUser,
//                        'idamount' => $BalancesOperation->idamounts,
//                        'value' => $params["montant"],
//                        'WinPurchaseAmount' => "0.000", 'Balance' => $params["newSoldeCashBalance"]]);

                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::From_CASH_Balance_BFS)->first();
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $user_balance= new user_balance(
                   ['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::From_CASH_Balance_BFS,
                       'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser, 'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000"
                       , 'Balance' => $params["newSoldeBFS"], 'PrixUnitaire' => 1]
               ) ;
               $user_balance->save() ;

//                $id_balance = DB::table('user_balances')->insertGetId(['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::From_CASH_Balance_BFS,
//                    'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser, 'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000"
//                    , 'Balance' => $params["newSoldeBFS"], 'PrixUnitaire' => 1]);
                break;
            case EventBalanceOperationEnum::ExchangeBFSToSMS :

                if (($params) == null) dd('throw exception');

                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations",
                    BalanceOperationsEnum::BFS_TO_SMSn_BFS)->first();
                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance= new user_balance(['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::BFS_TO_SMSn_BFS, 'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser,
                    'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000", 'Balance' => $params["newSoldeCashBalance"]]) ; $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId(
//                    ['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::BFS_TO_SMSn_BFS, 'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser,
//                        'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000", 'Balance' => $params["newSoldeCashBalance"]]);

                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::From_BFS_Balance_SMS)->first();
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance=new user_balance() ; $user_balance->save(['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::From_BFS_Balance_SMS,
                'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser, 'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000"
                , 'Balance' => $params["newSoldeBFS"], 'PrixUnitaire' => $params["PrixSms"]]) ;
//                $id_balance = DB::table('user_balances')->insertGetId(['ref' => $ref, 'idBalancesOperation' => BalanceOperationsEnum::From_BFS_Balance_SMS,
//                    'Date' => $date, 'idSource' => $idUser, 'idUser' => $idUser, 'idamount' => $BalancesOperation->idamounts, 'value' => $params["montant"], 'WinPurchaseAmount' => "0.000"
//                    , 'Balance' => $params["newSoldeBFS"], 'PrixUnitaire' => $params["PrixSms"]]);
                break;
            case EventBalanceOperationEnum::SendSMS:
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations",
                    BalanceOperationsEnum::EnvoyeSMS)->first();
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                $prix_sms = $seting->IntegerValue;
                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $user_balance=new user_balance([
                   'ref' => $ref,
                   'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
                   'Date' => $date,
                   'idSource' => $idUser,
                   'idUser' => $idUser,
                   'idamount' => $BalancesOperation->idamounts,
                   'value' => $prix_sms,
                   'WinPurchaseAmount' => "0.000",
                   'PrixUnitaire' => $prix_sms
               ]); $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId([
//                    'ref' => $ref,
//                    'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
//                    'Date' => $date,
//                    'idSource' => $idUser,
//                    'idUser' => $idUser,
//                    'idamount' => $BalancesOperation->idamounts,
//                    'value' => $prix_sms,
//                    'WinPurchaseAmount' => "0.000",
//                    'PrixUnitaire' => $prix_sms
//                ]);
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
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::From_public_User_BFS)->first();
                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $user_balance= new  user_balance([
                   'ref' => $ref,
                   'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
                   'Date' => $date,
                   'idSource' => $idUser,
                   'idUser' => $params['recipient'],
                   'idamount' => $BalancesOperation->idamounts,
                   'value' => $params["montant"],
                   'WinPurchaseAmount' => "0.000",
                   'Balance' => $newSoldeBFSRecipient
               ]) ;$user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId([
//                    'ref' => $ref,
//                    'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
//                    'Date' => $date,
//                    'idSource' => $idUser,
//                    'idUser' => $params['recipient'],
//                    'idamount' => $BalancesOperation->idamounts,
//                    'value' => $params["montant"],
//                    'WinPurchaseAmount' => "0.000",
//                    'Balance' => $newSoldeBFSRecipient
//                ]);
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::to_Other_Users_public_CB)->first();
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $user_balance=new  user_balance(
                   [
                       'ref' => $ref,
                       'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
                       'Date' => $date,
                       'idSource' => $idUser,
                       'idUser' => $idUser,
                       'idamount' => $BalancesOperation->idamounts,
                       'value' => $params["montant"],
                       'WinPurchaseAmount' => "0.000",
                       'Balance' => $newSoldeCashSender
                   ]
               ) ;
               $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId([
//                    'ref' => $ref,
//                    'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
//                    'Date' => $date,
//                    'idSource' => $idUser,
//                    'idUser' => $idUser,
//                    'idamount' => $BalancesOperation->idamounts,
//                    'value' => $params["montant"],
//                    'WinPurchaseAmount' => "0.000",
//                    'Balance' => $newSoldeCashSender
//                ]);
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
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::From_public_User_BFS)->first();
                $date = date('Y-m-d H:i:s');
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $user_balance= new user_balance(
                   [
                       'ref' => $ref,
                       'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
                       'Date' => $date,
                       'idSource' => $idUser,
                       'idUser' => $params['recipient'],
                       'idamount' => $BalancesOperation->idamounts,
                       'value' => $params["montant"],
                       'WinPurchaseAmount' => "0.000",
                       'Balance' => $newSoldeBFSRecipient
                   ]
               );
               $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId([
//                    'ref' => $ref,
//                    'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
//                    'Date' => $date,
//                    'idSource' => $idUser,
//                    'idUser' => $params['recipient'],
//                    'idamount' => $BalancesOperation->idamounts,
//                    'value' => $params["montant"],
//                    'WinPurchaseAmount' => "0.000",
//                    'Balance' => $newSoldeBFSRecipient
//                ]);
                $BalancesOperation = DB::table('balanceoperations')->where("idBalanceOperations", BalanceOperationsEnum::to_Other_Users_public_BFS)->first();
                $Count = DB::table('user_balances')->count();
                $ref = $BalancesOperation->idBalanceOperations . date('ymd') . substr((10000 + $Count + 1), 1, 4);
                $user_balance= new user_balance(
                    [
                        'ref' => $ref,
                        'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
                        'Date' => $date,
                        'idSource' => $idUser,
                        'idUser' => $idUser,
                        'idamount' => $BalancesOperation->idamounts,
                        'value' => $params["montant"],
                        'WinPurchaseAmount' => "0.000",
                        'Balance' => $newSoldeCashSender
                    ]
                ) ;
                $user_balance->save() ;
//                $id_balance = DB::table('user_balances')->insertGetId([
//                    'ref' => $ref,
//                    'idBalancesOperation' => $BalancesOperation->idBalanceOperations,
//                    'Date' => $date,
//                    'idSource' => $idUser,
//                    'idUser' => $idUser,
//                    'idamount' => $BalancesOperation->idamounts,
//                    'value' => $params["montant"],
//                    'WinPurchaseAmount' => "0.000",
//                    'Balance' => $newSoldeCashSender
//                ]);
                break;
        }
        }
    }
