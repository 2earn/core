<?php

use App\Models\SharesBalances;
use App\Models\TranslaleModel;
use App\Models\User;
use App\Services\Balances\BalancesFacade;
use App\Services\Users\UserTokenFacade;
use App\Services\Settings\SettingService;
use Carbon\Carbon;
use Core\Models\countrie;
use Core\Models\Setting;
use Core\Models\UserContact;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

if (!function_exists('getUserBalanceSoldes')) {
    function getUserBalanceSoldes($idUser, $amount)
    {
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
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $exception->getMessage();
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
if (!function_exists('getAdminCash')) {
    function getAdminCash()
    {
        $value = BalancesFacade::getSoldMainQuery('cash_balances')
            ->where('s.is_representative', 1)
            ->get();
        return $value->pluck('value')->toArray();
    }
}
if (!function_exists('getUserCash')) {
    function getUserCash($user)
    {
        $value = BalancesFacade::getSoldMainQuery('cash_balances')
            ->where('u.idUser', $user)
            ->get();
        return $value->pluck('value')->toArray();
    }
}

if (!function_exists('getUsertransaction')) {
    function getUsertransaction($user)
    {
        $count = DB::table('user_transactions')->where('idUser', $user)->count('*');
        if ($count > 0) {
            $value = DB::table('user_transactions')->select('autorised', 'cause', 'mnt')->where('idUser', $user)->get();
            $value = [$value[0]->autorised, $value[0]->cause, $value[0]->mnt];
        } else {
            $value = ["null", "null", "null"];
        }
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
        $us = User::where('idUser', $user)->first();
        return $us ? $us->fullphone_number : NULL;
    }
}
if (!function_exists('getUserByPhone')) {
    function getUserByPhone($phone, $apha2)
    {
        $id_country = countrie::where('apha2', strtoupper($apha2))->first()->id;
        $user = User::where('idCountry', $id_country)->where('mobile', $phone)->first();
        return $user ? $user->idUser : NULL;
    }
}


if (!function_exists('getUserByContact')) {
    function getUserByContact($phone)
    {
        $hours = getSettingService()->getIntegerValue('25');
        $user = UserContact::where('fullphone_number', $phone)->where('availablity', '1')->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ?', [$hours])
            ->orderBy('reserved_at')->pluck('idUser')->first();
        return $user ?? NULL;
    }
}


if (!function_exists('getSwitchBlock')) {
    function getSwitchBlock($id)
    {
        $hours = getSettingService()->getIntegerValue('29');
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
        $settingValues = getSettingService()->getIntegerValues(['16', '17', '18']);
        $initial_value = $settingValues['16'];
        $final_value = $initial_value * 5;
        $total_actions = $settingValues['18'];
        return ($final_value - $initial_value) / ($total_actions - 1) * ($selledActions + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
    }
}
if (!function_exists('getGiftedActions')) {
    function getGiftedActions($actions)
    {
        $settingValues = getSettingService()->getIntegerValues(['20', '18']);
        $max_bonus = $settingValues['20'];
        $total_actions = $settingValues['18'];

        $k = getSettingService()->getDecimalValue('21');

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
        return intval($actions * ($times - 1));
    }
}
if (!function_exists('actualActionValue')) {
    function actualActionValue($selled_actions, $formated = true)
    {
        $settingValues = getSettingService()->getIntegerValues(['16', '17', '18']);
        $initial_value = $settingValues['16'];
        $final_value = $settingValues['17'];
        $total_actions = $settingValues['18'];
        $x = ($final_value - $initial_value) / ($total_actions - 1) * ($selled_actions + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
        return $formated ? number_format($x, 2, '.', '') * 1 : $x;
    }
}

if (!function_exists('getSelledActions')) {
    function getSelledActions($withGiftedShares = false)
    {
        return $withGiftedShares ? SharesBalances::sum(DB::raw('value')) : SharesBalances::where('balance_operation_id', 44)->sum('value');
    }
}

if (!function_exists('getGiftedShares')) {
    function getGiftedShares()
    {

        return SharesBalances::whereNotIn('balance_operation_id', [44])->sum('value');
    }
}
if (!function_exists('getRevenuShares')) {
    function getRevenuShares()
    {
        return SharesBalances::selectRaw('SUM(amount) as total_sum')->first()->total_sum;
    }
}
if (!function_exists('getRevenuSharesReal')) {
    function getRevenuSharesReal()
    {
        return SharesBalances::selectRaw('SUM(real_amount) as total_sum')->first()->total_sum;
    }
}

if (!function_exists('getUserSelledActions')) {
    function getUserSelledActions($user)
    {
        return SharesBalances::where('beneficiary_id', $user)->selectRaw('SUM(value) as total_sum')
            ->first()->total_sum;
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
        return getUserActualActionsValue($user) - SharesBalances::where('beneficiary_id', $user)
                ->selectRaw('SUM(amount) as total_sum')
                ->first()
                ->total_sum;
    }
}
if (!function_exists('getExtraAdmin')) {
    function getExtraAdmin()
    {
        return auth()->user()->fullphone_number;
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
            } catch (DecryptException $exception) {
                Log::error($exception->getMessage());
            }

            $lang = substr($lang, -2);
        } else {
            try {
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
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
        if (Auth::check()) {
            $countryAuth = DB::table('countries')->where('id', '=', auth()->user()->idCountry)->get()->first();
            if (strtolower($country_code) != strtolower($countryAuth->apha2) && strtolower(getActifNumber()->isoP) != strtolower($country_code)) {
                $samePay = false;
            }
            if (strtolower(getActifNumber()->isoP) != strtolower($country_code) && strtolower($country_code) == strtolower($countryAuth->apha2)) {
                $num = collect(DB::select('select * from usercontactnumber where idUser = ? and mobile = ?', [Auth::user()->idUser, auth()->user()->mobile]))->first();
                DB::update('update usercontactnumber set active = 0 where idUser = ?', [auth()->user()->idUser]);
                DB::update('update usercontactnumber set active = 1 where id = ?', [$num->id]);
            }
        }
        return $samePay;
    }
}

if (!function_exists('getActifNumber')) {
    function getActifNumber()
    {
        if (Auth::check()) {
            return collect(DB::select('select * from usercontactnumber where idUser = ? and active = 1', [auth()->user()->idUser]))->first();
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
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        Log::channel('earnDebug')->debug('Client IP : ' . $clientIP . 'Details-Log : ' . $message);
    }
}


if (!function_exists('usdToSar')) {
    function usdToSar()
    {
        return getSettingService()->getDecimalValue('30');
    }
}

if (!function_exists('checkUserBalancesInReservation')) {
    function checkUserBalancesInReservation($idUser)
    {
        $reservation = getSettingService()->getIntegerValue('32');
        $result = DB::table('shares_balances as u')
            ->where('beneficiary_id', $idUser)
            ->select(DB::raw('TIMESTAMPDIFF(HOUR, ' . DB::raw('created_at') . ', NOW()))'))
            ->where('balance_operation_id', 44)
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('created_at') . ', NOW()) < ?', [$reservation])
            ->count();
        return $result ?? null;
    }
}

if (!function_exists('formatSolde')) {
    function formatSolde($solde, $decimals = 2)
    {
        if (is_null($solde)) {
            $solde = 0;
        }
        if ($decimals == -1) {
            return $solde;
        }
        return number_format($solde, $decimals, '.', ',');
    }
}
if (!function_exists('getDecimals')) {
    function getDecimals($number, $decimals = 2)
    {
        if (is_null($number)) {
            $number = 0;
        }
        return substr(number_format($number - intval($number), $decimals, '.', ','), 2);
    }
}

if (!function_exists('getUserDisplayedNameFromId')) {
    function getUserDisplayedNameFromId($id)
    {
        $user = User::find($id);
        return getUserDisplayedName($user->idUser);
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
    function getDiffOnDays($disaredDate)
    {
        $now = new DateTime();
        $input = DateTime::createFromFormat('Y-m-d', $disaredDate);
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
        match (config('app.name')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_earn', '2earn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_earn', 'dev_2earn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_earn', 'demo_2earn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_earn', 'prod_2earn', $viewSqlCode),
            'preprod.2earn.cash' => $viewSqlCode = str_replace('database_earn', 'preprod_2earn', $viewSqlCode),
        };

        match (config('app.name')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_name', '2earn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_name', 'dev_2earn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_name', 'demo_2earn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_name', 'prod_2earn', $viewSqlCode),
            'preprod.2earn.cash' => $viewSqlCode = str_replace('database_name', 'preprod_2earn', $viewSqlCode),
        };
        match (config('app.name')) {
            '2Earn.test' => $viewSqlCode = str_replace('database_learn', 'learn', $viewSqlCode),
            'dev.2earn.cash' => $viewSqlCode = str_replace('database_learn', 'dev_learn', $viewSqlCode),
            'demo.2earn.cash' => $viewSqlCode = str_replace('database_learn', 'demo_learn', $viewSqlCode),
            '2Earn.cash' => $viewSqlCode = str_replace('database_learn', 'prod_learn', $viewSqlCode),
            'preprod.2earn.cash' => $viewSqlCode = str_replace('database_learn', 'prod_learn', $viewSqlCode),
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
if (!function_exists('getSettingService')) {
    function getSettingService(): SettingService
    {
        return app(SettingService::class);
    }
}

if (!function_exists('getSettingParam')) {
    function getSettingParam($paramName)
    {
        return getSettingService()->getSettingByParameterName($paramName);
    }
}

if (!function_exists('getSettingDecimalParam')) {
    function getSettingDecimalParam($paramName, $default)
    {
        $value = getSettingService()->getDecimalByParameterName($paramName);
        return $value ?? $default;
    }
}

if (!function_exists('getSettingIntegerParam')) {
    function getSettingIntegerParam($paramName, $default)
    {
        $value = getSettingService()->getIntegerByParameterName($paramName);
        return $value ?? $default;
    }
}

if (!function_exists('getSettingStringParam')) {
    function getSettingStringParam($paramName, $default)
    {
        $value = getSettingService()->getStringByParameterName($paramName);
        return $value ?? $default;
    }
}

if (!function_exists('generateRandomWord')) {
    function generateRandomWord($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomWord = '';

        for ($i = 0; $i < $length; $i++) {
            $randomWord .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomWord;
    }
}

if (!function_exists('generateRandomText')) {
    function generateRandomText($wordCount, $wordLengthRange = [3, 10])
    {
        $randomText = '';

        for ($i = 0; $i < $wordCount; $i++) {
            $wordLength = rand($wordLengthRange[0], $wordLengthRange[1]);
            $randomText .= generateRandomWord($wordLength) . ' ';
        }

        return trim($randomText);
    }
}

if (!function_exists('generateUserToken')) {
    function generateUserToken()
    {
        return UserTokenFacade::getOrCreateToken();
    }
}
if (!function_exists('getBalanceCIView')) {
    function getBalanceCIView($balance)
    {
        if (!is_null($balance)) {
            return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
        } else {
            return view('parts.datatable.ci.ci-default');
        }
    }
}

if (!function_exists('createTranslaleModel')) {
    function createTranslaleModel($model, $translation, $value)
    {
        TranslaleModel::create(
            [
                'name' => TranslaleModel::getTranslateName($model, $translation),
                'value' => $value . ' AR',
                'valueFr' => $value . ' FR',
                'valueEn' => $value . ' EN',
                'valueTr' => $value . ' TR',
                'valueEs' => $value . ' ES',
                'valueRu' => $value . ' Ru',
                'valueDe' => $value . ' De',
            ]);
    }
}
if (!function_exists('getContainerType')) {
    function getContainerType()
    {
        return getSettingStringParam('container-type', 'container');
    }
}
