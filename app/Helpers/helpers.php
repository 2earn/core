<?php

use App\Models\User;
use App\Services\Balances\BalancesFacade;
use Carbon\Carbon;
use Core\Models\Setting;
use Core\Models\user_balance;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use App\Models\UserCurrentBalancehorisontal;
if (!function_exists('getUserBalanceSoldes')) {
    function getUserBalanceSoldes($idUser, $amount)
    {
        // CONVERTED IN BALANCES
        // from user current balances horizontale
        // remove joins
        // 7 selon solde
        return match ($amount) {
            1 => BalancesFacade::getCash($idUser),
            2 => BalancesFacade::getBfss($idUser),
            3 => BalancesFacade::getDiscount($idUser),
            4 => BalancesFacade::getTree($idUser),
            5 => BalancesFacade::getSms($idUser),
            default => BalancesFacade::getCash($idUser),
        };

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

if (!function_exists('getRegisterUpline')) {
    function getRegisterUpline($iduser)
    {
        $userData = User::leftJoin('metta_users', 'users.idUser', '=', 'metta_users.idUser')
            ->where('users.idUser', $iduser)
            ->first(['users.fullphone_number', 'metta_users.arFirstName', 'metta_users.enFirstName', 'metta_users.arLastName', 'metta_users.enLastName']);

        $fullName = $userData->arFirstName ?? $userData->enFirstName;
        $fullName .= ' ';
        $fullName .= $userData->arLastName ?? $userData->enLastName;

        if (empty(trim($fullName))) {
            $result = $userData->fullphone_number;
        } else {
            $result = $fullName;
        }
        return $result;
    }
}
if (!function_exists('getUserListCards')) {
    function getUserListCards()
    {
        // CHECKED IN BALANCES
        // --> TO CHECK
        // whereNotIn 4 == cash / bfs / discount /action

        $data = DB::table(function ($query) {
            $query->select('idUser', 'u.idamount', 'Date', 'u.idBalancesOperation', 'b.operation', DB::raw('CASE WHEN b.io = "I" THEN value ELSE -value END as value'))
                ->from('user_balances as u')
                ->join('balance_operations as b', 'u.idBalancesOperation', '=', 'b.id')
                ->whereNotIn('u.idamount', [4])
                ->orderBy('idUser')
                ->orderBy('u.idamount')
                ->orderBy('Date');
        }, 'a')
            ->select('a.idamount', DB::raw('SUM(a.value) as value'))
            ->groupBy('a.idamount')
            ->orderBy('a.idamount')
            ->union(DB::table('user_balances')
                ->select(DB::raw('7 as idamount'), DB::raw('SUM(value) as value'))
                ->where('idBalancesOperation', 48))
            ->orderBy('idamount')
            ->get();
        return $data->pluck('value')->toArray();

    }
}
if (!function_exists('getAdminCash')) {
    function getAdminCash()
    {
        // CHECKED IN BALANCES
        // user_balances -> current userbalances horisontale

        $value = DB::table('user_balances as u')
            ->select(DB::raw('SUM(CASE WHEN b.io = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balance_operations as b', 'u.idBalancesOperation', '=', 'b.id')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idamount', 1)
            ->where('s.is_representative', 1)
            ->get();
        return $value->pluck('value')->toArray();
    }
}
if (!function_exists('getUserCash')) {
    function getUserCash($user)
    {
        // CHECKED IN BALANCES

        // To remove


        $value = DB::table('user_balances as u')
            ->select(DB::raw('SUM(CASE WHEN b.io = "I" THEN u.value ELSE -u.value END) as value'))
            ->join('balance_operations as b', 'u.idBalancesOperation', '=', 'b.id')
            ->join('users as s', 'u.idUser', '=', 's.idUser')
            ->where('u.idamount', 1)
            ->where('u.idUser', $user)
            ->get();
        return $value->pluck('value')->toArray();
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


if (!function_exists('getUserByContact')) {
    function getUserByContact($phone)
    {
        $hours = Setting::Where('idSETTINGS', '25')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        $user = \Core\Models\UserContact::where('fullphone_number', $phone)->where('availablity', '1')->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$hours])
            ->orderBy('reserved_at')->pluck('idUser')->first();
        return $user ? $user : NULL;
    }
}


if (!function_exists('getSwitchBlock')) {
    function getSwitchBlock($id)
    {

        $hours = Setting::Where('idSETTINGS', '29')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        $user = \Core\Models\UserContact::where('id', $id)
            ->pluck('reserved_at')->first();
        if ($user) {
            $user = Carbon::parse($user);
            $block = $user->addHours($hours);
        }
        return $user ? $block : NULL;
    }
}


if (!function_exists('getHalfActionValue')) {
    function getHalfActionValue()
    {
        $selledActions = getSelledActions(true) * 1.05 / 2;
        $setting = Setting::WhereIn('idSETTINGS', ['16', '17', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $initial_value = $setting[0];
        $final_value = $initial_value * 5;
        $total_actions = $setting[2];
        return ($final_value - $initial_value) / ($total_actions - 1) * ($selledActions + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
    }
}
if (!function_exists('getGiftedActions')) {
    function getGiftedActions($actions)
    {
        $setting = Setting::WhereIn('idSETTINGS', ['20', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $max_bonus = $setting[0];
        $total_actions = $setting[1];

        $k = Setting::Where('idSETTINGS', '21')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();

        $a = (($total_actions * $max_bonus) / 100);
        $b = (1 - exp(-$k * $actions));
        $result = intval($a * $b);
        return $result;
    }
}
if (!function_exists('find_actions')) {
    function find_actions($result_final, $total_actions, $max_bonus, $k, $x)
    {
        $a = ($total_actions * $max_bonus) / 100;
        $epsilon = 0.0001; // tolérance pour la solution
        $actions_guess = $result_final / (1 + $x); // initial guess

        while (true) {
            $b = 1 - exp(-$k * $actions_guess);
            $result = intval($a * $b);
            $calculated_result_final = $result + $x * $actions_guess;

            if (abs($calculated_result_final - $result_final) < $epsilon) {
                return $actions_guess;
            }

            // Ajuster la valeur de guess en fonction de la différence
            $actions_guess -= ($calculated_result_final - $result_final) / (1 + $x);
        }
    }
}
if (!function_exists('getFlashGiftedActions')) {
    function getFlashGiftedActions($actions, $times)
    {
        $result = intval($actions * ($times - 1));
        return $result;
    }
}
if (!function_exists('actualActionValue')) {
    function actualActionValue($selled_actions, $formated = true)
    {
        $setting = Setting::WhereIn('idSETTINGS', ['16', '17', '18'])->orderBy('idSETTINGS')->pluck('IntegerValue');
        $initial_value = $setting[0];
        $final_value = $setting[1];
        $total_actions = $setting[2];
        $x = ($final_value - $initial_value) / ($total_actions - 1) * ($selled_actions + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
        return $formated ? number_format($x, 2, '.', '') * 1 : $x;
    }
}

if (!function_exists('getSelledActions')) {
    function getSelledActions($withGiftedShares = false)
    {
        if ($withGiftedShares) {
            return user_balance::where('idBalancesOperation', 44)->sum(DB::raw('value + gifted_shares'));
        } else {
            return user_balance::where('idBalancesOperation', 44)->sum('value');
        }
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
        return \Core\Models\user_balance::where('idBalancesOperation', 44)->where('idUser', $user)->selectRaw('SUM(value + gifted_shares) as total_sum')->first()->total_sum;
    }
}

if (!function_exists('getUserActualActionsValue')) {
    function getUserActualActionsValue($user)
    {
        return actualActionValue(getSelledActions(true)) * getUserSelledActions($user);
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
        return Setting::Where('idSETTINGS', '30')->orderBy('idSETTINGS')->pluck('DecimalValue')->first();
    }
}

if (!function_exists('checkUserBalancesInReservation')) {
    function checkUserBalancesInReservation($idUser)
    {
        $reservation = Setting::Where('idSETTINGS', '32')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
        // CHECKED IN BALANCES
        // user_balances -> action

        $result = DB::table('user_balances as u')
            ->where('idUser', $idUser)
            ->select(DB::raw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()))'))
            ->where('idBalancesOperation', 44)
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()) < ?', [$reservation])
            ->count();
        return $result ?? null;
    }
}

if (!function_exists('formatSolde')) {
    function formatSolde($solde, $decimals = 2)
    {
        if ($decimals == -1) {
            return $solde;
        }
        return number_format($solde, $decimals, '.', ',');
    }
}
if (!function_exists('getDecimals')) {
    function getDecimals($number, $decimals = 2)
    {
        return substr(number_format($number - intval($number), $decimals, '.', ','), 2);
    }
}

if (!function_exists('getUserDisplayedName')) {
    function getUserDisplayedName($idUser = null)
    {
        if (is_null($idUser)) {
            $idUser = auth()->user()->idUser;
        }
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $idUser)->first());
        $user = DB::table('users')->where('idUser', $idUser)->first();
        $displayedName = "";
        if (config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
            if (isset($usermetta_info['arFirstName']) && isset($usermetta_info['arLastName']) && !empty($usermetta_info['arFirstName']) && !empty($usermetta_info['arLastName'])) {
                $displayedName = $usermetta_info['arFirstName'] . ' ' . $usermetta_info['arLastName'];
            } else {
                if ((isset($usermetta_info['arFirstName']) && !empty($usermetta_info['arFirstName'])) || (isset($usermetta_info['arLastName']) && !empty($usermetta_info['arLastName']))) {
                    if (isset($usermetta_info['arFirstName']) && !empty($usermetta_info['arFirstName'])) {
                        $displayedName = $usermetta_info['arFirstName'];
                    }
                    if (isset($usermetta_info['arLastName']) && !empty($usermetta_info['arLastName'])) {
                        $displayedName = $usermetta_info['arLastName'];
                    }
                } else {
                    $displayedName = $user->fullphone_number;
                }
            }
        else
            if (isset($usermetta_info['enFirstName']) && isset($usermetta_info['enLastName']) && !empty($usermetta_info['enFirstName']) && !empty($usermetta_info['enLastName'])) {
                $displayedName = $usermetta_info['enFirstName'] . ' ' . $usermetta_info['enLastName'];
            } else {
                if ((isset($usermetta_info['enFirstName']) && !empty($usermetta_info['enFirstName'])) || (isset($usermetta_info['enLastName']) && !empty($usermetta_info['enLastName']))) {
                    if (isset($usermetta_info['enFirstName']) && !empty($usermetta_info['enFirstName'])) {
                        $displayedName = $usermetta_info['enFirstName'];
                    }
                    if (isset($usermetta_info['enLastName']) && !empty($usermetta_info['enLastName'])) {
                        $displayedName = $usermetta_info['enLastName'];
                    }
                } else {
                    $displayedName = $user->fullphone_number;
                }
            }
        return $displayedName;
    }

}
if (!function_exists('formatNotification')) {
    function formatNotification($notification)
    {
        $notificationText = '';
        switch ($notification->type) {
            case 'App\Notifications\contact_registred':
                $notificationText = Lang::get('New contact registred') . ' ' . $notification->data['fullphone_number'];
                break;
            case 1:
                echo "i equals 1";
                break;
            case 2:
                echo "i equals 2";
                break;
        }
        return $notificationText;
    }
}

if (!function_exists('time_ago')) {

    function time_ago(\Datetime $date)
    {
        $time_ago = '';
        $diff = $date->diff(new \Datetime('now'));
        if (($t = $diff->format("%y")) > 0)
            $time_ago = $t . ' years';
        else if (($t = $diff->format("%m")) > 0)
            $time_ago = $t . ' months';
        else if (($t = $diff->format("%d")) > 0)
            $time_ago = $t . ' days';
        else if (($t = $diff->format("%H")) > 0)
            $time_ago = $t . ' hours';
        else
            $time_ago = 'minutes';

        return $time_ago . ' ago (' . $date->format('M j, Y') . ')';
    }
}

if (!function_exists('isValidEmailAdressFormat')) {
    function isValidEmailAdressFormat($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('getProfileMsgErreur')) {
    function getProfileMsgErreur($typeErreur)
    {
        return Lang::get('Identify_' . $typeErreur);
    }
}

if (!function_exists('checkExpiredSoonInternationalIdentity')) {
    function getDiffOnDays($disiredDate)
    {
        $now = new DateTime();
        $input = DateTime::createFromFormat('Y-m-d', $disiredDate);
        return $now->diff($input)->format("%r%a");
    }
}

if (!function_exists('getValidCurrentDateTime')) {
    function getValidCurrentDateTime($date)
    {

        if (is_null($date)) {
            return null;
        }
        if (strlen($date) > 10) {
            $datetime = new \DateTime(date($date));
        } else {
            $datetime = new \DateTime(date($date . ' H:i:s'));
        }
        return $datetime->format('Y-m-d H:i:s');
    }
}

if (!function_exists('formatSqlWithEnv')) {
    function formatSqlWithEnv($viewSqlCode)
    {
        match (env('APP_NAME', '2Earn.test')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_earn', '2earn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_earn', 'dev_2earn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_earn', 'demo_2earn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_earn', 'prod_2earn', $viewSqlCode),
        };

        match (env('APP_NAME', '2Earn.test')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_name', '2earn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_name', 'dev_2earn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_name', 'demo_2earn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_name', 'prod_2earn', $viewSqlCode),
        };
        match (env('APP_NAME', '2Earn.test')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_learn', 'learn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_learn', 'dev_learn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_learn', 'demo_learn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_learn', 'prod_learn', $viewSqlCode),
        };
        return $viewSqlCode;

    }
}

if (!function_exists('getSqlFromPath')) {
    function getSqlFromPath($fileName)
    {

        $path = database_path('sql/' . $fileName . '.sql');
        if (!File::exists($path)) {
            throw new Exception('Invalid sql Path');
        }
        return File::get($path);
    }
}



