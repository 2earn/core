<?php


use Carbon\Carbon;
use Core\Interfaces\IBalanceOperationRepositoty;
use Core\Interfaces\IUserBalancesRepository;
use Core\Services\settingsManager;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Core\Services\BalancesManager;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use Core\Models\Setting;


if (!function_exists('getUserBalanceSoldes')) {
    function getUserBalanceSoldes($idUser, $amount)
    {
        $result = DB::table('user_balances as u')
            ->select('u.idUser', 'u.idamount', DB::raw('SUM(CASE WHEN b.IO = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balanceoperations as b', 'u.idBalancesOperation', '=', 'b.idBalanceOperations')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idUser', $idUser)
            ->where('u.idamount', $amount)
            ->groupBy('u.idUser', 'u.idamount')
            ->first();


        if ($result) {
            return $result->value;
        } else {
            return 0.000;
        }
    }
}
if (!function_exists('validatePhone')) {
    function validatePhone($phone, $ccode)
    {
        try {
            $country = DB::table('countries')->where('phonecode', $ccode)->first();

            $phone = new PhoneNumber($phone, $country->apha2);
            $phone->formatForCountry($country->apha2);
            return "1";
        } catch (\Exception $exp) {
                return $exp->getMessage();
            }
    }
}

if (!function_exists('getUserListCards')) {
    function getUserListCards()
    {
        $data = DB::table(function ($query) {
            $query->select('idUser', 'u.idamount', 'Date', 'u.idBalancesOperation', 'b.Designation', DB::raw('CASE WHEN b.IO = "I" THEN value ELSE -value END as value'))
                ->from('user_balances as u')
                ->join('balanceoperations as b', 'u.idBalancesOperation', '=', 'b.idBalanceOperations')
                ->whereNotIn('u.idamount', [4])
                ->orderBy('idUser')
                ->orderBy('u.idamount')
                ->orderBy('Date');
        }, 'a')
            ->select('a.idamount', DB::raw('SUM(a.value) as value'))
            ->groupBy('a.idamount')
            ->orderBy('a.idamount') // Ajout de l'ordre par idamount
            ->union(DB::table('user_balances')
                ->select(DB::raw('7 as idamount'), DB::raw('SUM(value) as value'))
                ->where('idBalancesOperation', 48))
            ->orderBy('idamount') // Ajout de l'ordre par idamount pour le second ensemble de données
            ->get();
        $dataArray = $data->pluck('value')->toArray();
        return $dataArray;
    }
}
if (!function_exists('getAdminCash')) {
    function getAdminCash()
    {
        $value = DB::table('user_balances as u')
            ->select(DB::raw('SUM(CASE WHEN b.IO = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balanceoperations as b', 'u.idBalancesOperation', '=', 'b.idBalanceOperations')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idamount', 1)
            ->where('s.is_representative', 1)
            ->get();
        $dataArray = $value->pluck('value')->toArray();
        return $dataArray;
    }
}
if (!function_exists('getUserCash')) {
    function getUserCash($user)
    {
        $value = DB::table('user_balances as u')
            ->select(DB::raw('SUM(CASE WHEN b.IO = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balanceoperations as b', 'u.idBalancesOperation', '=', 'b.idBalanceOperations')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idamount', 1)
            ->where('u.idUser', $user)
            ->get();
        $dataArray = $value->pluck('value')->toArray();
        return $dataArray;
    }
}

if (!function_exists('getUsertransaction')) {
    function getUsertransaction($user)
    {
        $count = DB::table('user_transactions')
            ->where('idUser', $user)
            ->count('*');
        if ($count > 0) {
            $value = DB::table('user_transactions')
                ->select('autorised', 'cause', 'mnt')
                ->where('idUser', $user)
                ->get();
            $value = [$value[0]->autorised, $value[0]->cause, $value[0]->mnt];
        } else
            $value = ["null", "null", "null"];

        return $value;
    }
}
if (!function_exists('delUsertransaction')) {
    function delUsertransaction($user)
    {

        $del = DB::table('user_transactions')
            ->where('idUser', $user)
            ->delete();

        return response()->json($del);
    }
}
if (!function_exists('getPhoneByUser')) {
    function getPhoneByUser($user)
    {

        $us = \App\Models\User::where('idUser', $user)->first();
        return $us ? $us->fullphone_number : NULL;
    }
}
if (!function_exists('getUserByPhone')) {
    function getUserByPhone($phone, $apha2)
    {
        $id_country = \Core\Models\countrie::where('apha2', strtoupper($apha2))->first()->id;
        $user = \App\Models\User::where('idCountry', $id_country)->where('mobile', $phone)->first();
        return $user ? $user->idUser : NULL;
    }
}


// parrainage proactif
if (!function_exists('getUserByContact')) {
    function getUserByContact($phone)
    {
        $hours = \Core\Models\Setting::Where('idSETTINGS', '25')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        $user = \Core\Models\UserContact::where('fullphone_number', $phone)->where('availablity', '1')->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$hours])
            ->orderBy('reserved_at')->pluck('idUser')->first();
        return $user ? $user : NULL;
    }
}


if (!function_exists('getSwitchBlock')) {
    function getSwitchBlock($id)
    {

        $hours = \Core\Models\Setting::Where('idSETTINGS', '29')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        $user = \Core\Models\UserContact::where('id', $id)
            ->pluck('reserved_at')->first();
        if ($user) {
            $user = Carbon::parse($user);
            $block = $user->addHours($hours);
        }
        return $user ? $block : NULL;
    }
}
if (!function_exists('getGiftedActions')) {
    function getGiftedActions($actions)
    {
        $setting = \Core\Models\Setting::WhereIn('idSETTINGS', ['20', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $max_bonus = $setting[0];
        $total_actions = $setting[1];
        $k = \Core\Models\Setting::Where('idSETTINGS', '21')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();

        $a = (($total_actions * $max_bonus) / 100);
        $b = (1 - exp(-$k * $actions));
        return intval($a * $b);
    }
}

if (!function_exists('actualActionValue')) {
    function actualActionValue($selled_actions)
    {
        $setting = \Core\Models\Setting::WhereIn('idSETTINGS', ['16', '17', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $initial_value = $setting[0];
        $final_value = $setting[1];
        $total_actions = $setting[2];
        $x = ($final_value - $initial_value) / ($total_actions - 1) * ($selled_actions + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
        return number_format($x, 2, '.', '') * 1;
    }
}

if (!function_exists('getSelledActions')) {
    function getSelledActions()
    {
        return \Core\Models\user_balance::where('idBalancesOperation', 44)->sum('value');
    }
}
if (!function_exists('getGiftedShares')) {
    function getGiftedShares()
    {
        return \Core\Models\user_balance::where('idBalancesOperation', 44)->sum('gifted_shares');
    }
}
if (!function_exists('getRevenuShares')) {
    function getRevenuShares()
    {
        return \Core\Models\user_balance::where('idBalancesOperation', 44)
            ->selectRaw('SUM((value + gifted_shares)*cast(PU as decimal(10,2))) as total_sum')->first()->total_sum;
    }
}
if (!function_exists('getRevenuSharesReal')) {
    function getRevenuSharesReal()
    {
        return \Core\Models\user_balance::where('idBalancesOperation', 44)
            ->selectRaw('SUM(Balance) as total_sum')->first()->total_sum;
    }
}

if (!function_exists('getUserSelledActions')) {
    function getUserSelledActions($user)
    {
        //nbr des actions pour un utilisateur
        return \Core\Models\user_balance::where('idBalancesOperation', 44)->where('idUser', $user)->selectRaw('SUM(value + gifted_shares) as total_sum')->first()->total_sum;
    }
}

if (!function_exists('getUserActualActionsValue')) {
    function getUserActualActionsValue($user)
    {
        return actualActionValue(getSelledActions()) * getUserSelledActions($user);
    }
}

if (!function_exists('getUserActualActionsProfit')) {
    function getUserActualActionsProfit($user)
    {
        $expences = \Core\Models\user_balance::where('idBalancesOperation', 44)->where('idUser', $user)->selectRaw('SUM((value + gifted_shares) * PU) as total_sum')->first()->total_sum;
        return getUserActualActionsValue($user) - $expences;
    }
}
if (!function_exists('getExtraAdmin')) {
    function getExtraAdmin()
    {
        $user = auth()->user()->fullphone_number;
        return $user;
    }
}
if (!function_exists('getLangNavigation')) {
    function getLangNavigation()
    {
        $lang = "";
        if (Cookie::has('PreferedLangue')) {
            $lang = Cookie::get('PreferedLangue');
            try {
                $lang = Crypt::decrypt(Cookie::get('PreferedLangue'), false);
            } catch (DecryptException $e) {
            }

            $lang = substr($lang, -2);
        } else {
            try {
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            } catch (Exception $ex) {
            }
        }
        if ($lang == "") {
            $ip = ip2long(request()->ip());
            $ip = long2ip($ip);
            if ($ip = '0.0.0.0') {
                $ip = "41.226.181.241";
            }

            $IP = $ip;
            $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
            $details = json_decode($json, true);
            $country_code = $details['country'];
            $ss = DB::table('countries')->where('apha2', '=', $country_code)->get()->first();
            $lang = $ss->lang;
        }
        if ($lang == "") {
            {
                $lang = "en";
            }
        }
        return $lang;
    }
}


if (!function_exists('getLocationByIP')) {
    function getLocationByIP()
    {
        $samePay = true;
        $ip = ip2long(request()->ip());


        $ip = long2ip($ip);
        earnDebug("ippp1: " . $ip);
        if ($ip == '0.0.0.0' || $ip == '127.0.0.1') {
            earnDebug("ipppChange: " . $ip);
            $ip = "41.226.181.241";
        }

        $IP = $ip;
        earnDebug("ippp: " . $ip);
        $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
        $details = json_decode($json, true);
        $country_code = $details['country'];
        if (\Illuminate\Support\Facades\Auth::check()) {
            $authUser = \Illuminate\Support\Facades\Auth::user();
            $countryAuth = DB::table('countries')->where('id', '=', $authUser->idCountry)->get()->first();
            if (strtolower($country_code) != strtolower($countryAuth->apha2) && strtolower(getActifNumber()->isoP) != strtolower($country_code)) {
                $samePay = false;
            }
            if (strtolower(getActifNumber()->isoP) != strtolower($country_code) && strtolower($country_code) == strtolower($countryAuth->apha2)) {
                $num = collect(DB::select('select * from usercontactnumber where idUser = ? and mobile = ?', [Auth::user()->idUser, $authUser->mobile]))->first();

                DB::update('update usercontactnumber set active = 0 where idUser = ?', [$authUser->idUser]);
                DB::update('update usercontactnumber set active = 1 where id = ?', [$num->id]);
            }
        }
        return $samePay;
    }
}


if (!function_exists('getActifNumber')) {
    function getActifNumber()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $authUser = \Illuminate\Support\Facades\Auth::user();
            return collect(DB::select('select * from usercontactnumber where idUser = ? and active = 1', [Auth::user()->idUser]))->first();
        }
        return false;
    }
}

if (!function_exists('getCountryByIso')) {
    function getCountryByIso($iso)
    {

        $s = collect(DB::select('select name from countries where apha2 = ? ', [$iso]))->first();
        return $s->name;
    }
}

if (!function_exists('earnDebug')) {
    function earnDebug($message)
    {
        $clientIP = "";
        try {
            $clientIP = request()->ip() . "   " . request()->server->get('HTTP_SEC_CH_UA');
        } catch (err) {
        }

        Log::channel('earnDebug')->debug('Client IP : ' . $clientIP . 'Details-Log : ' . $message);
    }
}

if (!function_exists('usdToSar')) {
    function usdToSar()
    {
        $k = \Core\Models\Setting::Where('idSETTINGS', '30')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();
        return $k;
    }

    if (!function_exists('checkUserBalancesInReservation')) {
        function checkUserBalancesInReservation($idUser)
        {
            $reservation = Setting::Where('idSETTINGS', '32')
                ->orderBy('idSETTINGS')
                ->pluck('IntegerValue')
                ->first();
            $result = DB::table('user_balances as u')
                ->where('idUser', $idUser)
                ->select(DB::raw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()))'))
                ->where('idBalancesOperation', 44)
                ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()) < ?', [$reservation])
                ->count();
            return $result ? $result : null;
        }
    }
}
