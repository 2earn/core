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
                $soldesLine = DB::table('user_current_balance_horisentals')->where('user_id', $idUserUpline)->first();
                SMSBalances::addLine(
                    [
                        'balance_operation_id' =>BalanceOperationsEnum::Achat_SMS_SMS->value,
                        'operator_id' => Balances::SYSTEM_SOURCE_ID,
                        'beneficiary_id' => $idUserUpline,
                        'reference' => Balances::getReference(BalanceOperationsEnum::Achat_SMS_SMS->value),
                        'description' => 'TO DO DESCRIPTION',
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
                $initialDiscount = DB::table('settings')->where("ParameterName", "=", 'INITIAL_DISCOUNT')->pluck('IntegerValue');
                $initialTree = DB::table('settings')->where("ParameterName", "=", 'INITIAL_TREE')->pluck('IntegerValue');
                $initialChance = DB::table('settings')->where("ParameterName", "=", 'INITIAL_CHANCE')->pluck('IntegerValue');

                DiscountBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_DB->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_DB->value),
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $initialDiscount,
                    'current_balance' => $initialDiscount
                ]);
                TreeBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_TREE->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_TREE->value),
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $initialTree,
                    'current_balance' => $initialTree
                ]);
                ChanceBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::INITIAL_CHANE->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::INITIAL_CHANE->value),
                    'description' => 'TO DO DESCRIPTION',
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
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::From_CASH_Balance_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeBFS"]
                ]);
                break;
            case EventBalanceOperationEnum::ExchangeBFSToSMS :

                if (($params) == null) dd('throw exception');

                $ref = BalancesFacade::getReference(BalanceOperationsEnum::BFS_TO_SM_SN_BFS);
                // TO FILL
                $oldSMSSOLD = 0;
                $seting = DB::table('settings')->where("idSETTINGS", "=", SettingsEnum::Prix_SMS->value)->first();
                $prix_sms = $seting->IntegerValue;

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::BFS_TO_SM_SN_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $params["newSoldeCashBalance"]
                ]);

                SMSBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_BFS_BALANCE_SMS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => intdiv($params["montant"], $prix_sms),
                    'sms_price' => $prix_sms,
                    'current_balance' => $params["montant"] + $oldSMSSOLD
                ]);

                break;
            case EventBalanceOperationEnum::SendSMS:
                $ref = BalancesFacade::getReference(BalanceOperationsEnum::Achat_SMS_SMS->value);
                // TO FILL
                $oldSMSSOLD = 1;
                SMSBalances::addLine(
                    [
                        'balance_operation_id' => BalanceOperationsEnum::Achat_SMS_SMS->value,
                        'operator_id' => $idUser,
                        'beneficiary_id' => $idUser,
                        'reference' => $ref,
                        'description' => 'TO DO DESCRIPTION',
                        'value' => 1,
                        'current_balance' => $oldSMSSOLD--
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

                $ref = BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value);

                CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_CB->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeCashSender
                ]);

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeBFSRecipient
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

                $ref = BalancesFacade::getReference(BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS);

                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::TO_OTHER_USERS_PUBLIC_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeCashSender
                ]);
                BFSsBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::FROM_PUBLIC_USER_BFS->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $params['recipient'],
                    'reference' => $ref,
                    'description' => 'TO DO DESCRIPTION',
                    'value' => $params["montant"],
                    'current_balance' => $newSoldeBFSRecipient
                ]);
                break;
        }
    }
}
