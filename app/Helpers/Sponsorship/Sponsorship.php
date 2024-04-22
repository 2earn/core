<?php

namespace App\Helpers\Sponsorship;

use App\DAL\UserBalancesRepository;
use App\Models\User;
use Core\Enum\AmoutEnum;
use Core\Models\Setting;
use Core\Models\user_balance;
use Core\Services\UserBalancesHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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

    public function __construct()
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

    public function checkDelayedSponsorship($sponsor): bool
    {
        $idUpline=$nombredAchat= $maxNombredAchat= 0;
        if ($idUpline = 0) {

            // soit avalibility =0
            //soit avalability = 1 + les delais dépassé
            $user = User::where('reserved_by', $sponsor->idUser)
                ->where('idUpline', '0')
                ->where('availablity', '0')
                ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) > ?', [$this->retardatifReservation])
                ->orderBy('reserved_at')
                ->pluck('idUser')
                ->first();
            if ($user) {
                // select from user balances where id balseces = 44 + balseces.iduser = user.iduser + limt 3 + order by date ASC
                // update uline user
            }
        }
    }

    public function executeDelayedSponsorship($sponsoredUser): bool
    {
        return true;
    }

    public function checkProactifSponsorship($sponsor): ?User
    {
        $idUpline=$nombredAchat= $maxNombredAchat= 0;
        if ($idUpline = 0) {
            $user = User::where('reserved_by', $sponsor->idUser)
                ->where('availablity', '1')
                ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$this->bookingHours])
                ->orderBy('reserved_at')
                ->pluck('idUser')
                ->first();
            if ($user) {
                // add commission == return id user
                // update idupline table user iduser
            } else {
                // update idupline table user =11111
            }
        } else {
            if ($idUpline !== 11111111) {
                //check field number of achat < variable de setting
                if($nombredAchat<=$maxNombredAchat)
                {
                    // add commission
                }
            }
        }

        return $user ? $user : NULL;
    }

    public function createUserBalances($ref, $idBalancesOperation, $idSource, $reserve, $idAmount, $value, $giftedShares, $PU, $winPurchaseAmount, $description, $balance)
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
    }


    public function executeProactifSponsorship($reserve, $reciver, $number_of_action, $gift, $PU, $balancesManager, $fullphone_number): bool
    {
        $Count = DB::table('user_balances')->count();
        $ref = "44" . date('ymd') . substr((10000 + $Count + 1), 1, 4);
        $amount = ($number_of_action + $gift) * $PU * $this->amount / 100;

        $this->createUserBalances(
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

        $this->createUserBalances(
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
            $balancesManager->getBalances(auth()->user()->idUser)->soldeCB + $amount * $this->amountCash / 100
        );

        $this->createUserBalances(
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
            $balancesManager->getBalances(auth()->user()->idUser)->soldeBFS + $amount * $this->amountBFS / 100
        );
    }

}
