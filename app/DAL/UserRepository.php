<?php

namespace App\DAL;

use App\Models\User;
use Core\Enum\AmoutEnum;
use Core\Models\AuthenticatedUser;
use Core\Interfaces\IUserRepository;
use Core\Models\metta_user;
use Core\Models\user_earn;
use Core\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class  UserRepository implements IUserRepository
{
    public function getAllMettaUser()
    {
        return DB::table('metta_users')
            ->get();
    }

    public function getAllUsersErans()
    {
        return DB::table('user_earns')
            ->get();
    }

    public function getAllUsers()
    {
        return DB::table('users')
            ->get();
    }

    public function getUserContacts($idUser)
    {
        return DB::table('user_contacts')
            ->where('idUser', $idUser)->get();
    }

    public function addUserContact(UserContact $userContact)
    {

        UserContact::create(
            [
                'idUser' => $userContact->idUser,
                'name' => $userContact->name,
                'lastName' => $userContact->lastName,
                'mobile' => $userContact->mobileNumber,
                'availablity' => $userContact->availablity,
                'disponible' => $userContact->disponible
            ]
        );
    }

    public function updateUserContact(UserContact $userContact)
    {
//        $result = UserContact::find($userContact->id)->exists();
        $uc = new userContact();

        if (UserContact::find($userContact->id)) {
            $uc = $userContact::find($userContact->id);

        }
        $uc->lastName = $userContact->lastName;
        $uc->Name = $userContact->name;
        $uc->idUser = $userContact->idUser;
        $uc->mobile = $userContact->mobile;
        $uc->availablity = $userContact->availablity;
        $uc->disponible = $userContact->disponible;
        $uc->fullphone_number = $userContact->fullphone_number;
        $uc->phonecode = $userContact->phonecode;
//        $uc = userContact::updateOrCreate(
//            ['id' => $userContact->id]
//        );
        $uc->save();
    }

    public function getUserContactsById($id)
    {
        $result = UserContact::find($id);
        return $result;
    }

    public function getUserById($id)
    {
        $result = User::find($id);
        return $result;
    }

    public function getAuthenticatedUser()
    {
        if (!Auth::user()) {
            return null;
        }
        return Auth::user();
    }

    public function getUserByMobile($mobile, $idContry, $pass)
    {

        $user = DB::table('users')
            ->where([
                ['mobile', '=', $mobile],
                ['idCountry', '=', $idContry],
            ])
            ->get()->first();
//     $u2 =   User::find($user->id) ;
//        $u2->password = Hash::make($testpass);
//        $u2->save();
//        return null ;
        if (!$user)
            return null;
        if (!Hash::check($pass, $user->password))
            return null;
        return $user;
    }


    public function getUserByMobileContry($mobile, $idContry)
    {
        $user = DB::table('users')
            ->where([
                ['mobile', '=', $mobile],
                ['idCountry', '=', $idContry],
            ])
            ->get()->first();
        if (!$user)
            return null;
        return $user;
    }


    public function loginUser($user, $remenber)
    {
        $minute = 1;
        $f = DB::table('settings')
            ->where('idSETTINGS', '=', 14)->get()->first();
        if ($f)
            $minute = $f->DecimalValue;
        Auth::login($user, $remenber);
        $key = 'Expired'. $user->idUser;
        Session::put($key, Carbon::now()->addMinute($minute));
    }
    public function logoutUser()
    {
        Auth::logout();
        Cache::flush();
//        Session::flush();

    }
    public function getUserByFullnumber($number)
    {
        $f = DB::table('users')
            ->where('fullphone_number', '=', $number)->get()->first();
        return $f;
    }

    public function createuser(User $user)
    {
        $user->save();
    }

    public function getSoldeUserByAmount($idUser, AmoutEnum $amount)
    {
        return
            DB::table('calculated_userbalances')
                ->where('idUser', $idUser)
                ->where('idamounts', $amount)
                ->first()->solde ?? 0;
    }

    public function getUserEarnByIdUser($iduser)
    {
        return
            DB::table('user_earns')
                ->where('idUser', $iduser)
                ->get()
                ->first();
    }

    public function createmettaUser(metta_user $metta_user)
    {
        // TODO: Implement createmettaUser() method.
        if (!metta_user::where('idUser', $metta_user->idUser)->exists()) {
            $metta_user->save();
        }

    }
    public function createUserEarn(user_earn $userEarn)
    {
        // TODO: Implement createmettaUser() method.
        if (!user_earn::where('idUser', $userEarn->idUser)->exists()) {
            $userEarn->save();
        }
    }
    public function getConditionalMettaUser($attribute, $value)
    {

        $user = DB::table('metta_users')
            ->where([
                [$attribute, '=', $value]
            ])
            ->get()->first();
        if (!$user)
            return null;
        return $user;
    }
    public function getConditionalUser($attribute, $value)
    {

        $user = DB::table('users')
            ->where([
                [$attribute, '=', $value]
            ])
            ->get()->first();
        if (!$user)
            return null;
        return $user;
    }
}
