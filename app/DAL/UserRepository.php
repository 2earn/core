<?php

namespace App\DAL;

use App\Enums\BalanceEnum;
use App\Enums\StatusRequest;
use App\Models\ContactUser;
use App\Models\MettaUser;
use App\Models\User;
use Carbon\Carbon;
use App\Interfaces\IUserRepository;
use App\Models\user_earn;
use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class  UserRepository implements IUserRepository
{
    public function getAllMettaUser()
    {
        return MettaUser::all();
    }

    public function getAllUsersErans()
    {
        return DB::table('user_earns')->get();
    }

    public function getAllUsers()
    {
        return DB::table('users')->get();
    }

    public function getUserContacts($idUser)
    {
        return DB::table('user_contacts')->where('idUser', $idUser)->get();
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

    public function addUserContactV2(ContactUser $contactUser)
    {
        ContactUser::create(
            [
                'idUser' => $contactUser->idUser,
                'idContact' => $contactUser->idContact,
                'name' => $contactUser->name,
                'lastName' => $contactUser->lastName,
                'mobile' => $contactUser->mobile,
                'availablity' => $contactUser->availablity,
                'disponible' => $contactUser->disponible
            ]
        );
    }

    public function updateUserContact(UserContact $userContact)
    {
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
        $uc->save();
    }

    public function updateUserContactV2($id, ContactUser $contactUser)
    {
        $uc = ContactUser::find($id);
        $uc->idUser = $contactUser->idUser;
        $uc->idContact = $contactUser->idContact;
        $uc->lastName = $contactUser->lastName;
        $uc->Name = $contactUser->name;
        $uc->mobile = $contactUser->mobile;
        $uc->fullphone_number = $contactUser->fullphone_number;
        $uc->phonecode = $contactUser->phonecode;
        $uc->save();
    }

    public function getUserContactsById($id)
    {
        return UserContact::find($id);
    }

    public function getContactsUserById($id)
    {
        return ContactUser::find($id);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }


    public function getUserByMobile($mobile, $idContry, $pass)
    {

        $user = DB::table('users')
            ->where([
                ['mobile', '=', $mobile],
                ['idCountry', '=', $idContry],
            ])
            ->get()->first();
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
        $key = 'Expired' . $user->idUser;
        Session::put($key, Carbon::now()->addMinute(floatval($minute)));
    }

    public function logoutUser()
    {
        Auth::user()->tokens()->delete();
        Auth::logout();
        Cache::flush();
    }

    public function getUserByFullnumber($number)
    {
        return DB::table('users')->where('fullphone_number', '=', $number)->get()->first();
    }

    public function createuser(User $user)
    {
        $user->save();
    }

    public function getSoldeUserByAmount($idUser, BalanceEnum $amount)
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

    public function createmettaUser(MettaUser $metta_user)
    {
        // TODO: Implement createmettaUser() method. ..
        if (!MettaUser::where('idUser', $metta_user->idUser)->exists()) {
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

        $user = MettaUser::where($attribute, '=', $value)->first();
        if (!$user)
            return null;
        return $user;
    }

    public function getConditionalUser($attribute, $value)
    {
        $user = DB::table('users')
            ->where([[$attribute, '=', $value]])
            ->get()->first();
        if (!$user)
            return null;
        return $user;
    }

    public function initNewUser($status = StatusRequest::Registred->value)
    {
        $newUser = new User();
        // Delegate next id generation to a single responsibility method
        $newUser->idUser = $this->getNextUserId();
        $newUser->status = $status;
        return $newUser;
    }

    /**
     * Get the next idUser value (max(iduser) + 1). Returns 1 when there are no users.
     * This version will update the MAX_USER_ID setting so subsequent calls use the stored value.
     *
     * @return int
     */
    public function getNextUserId(): int
    {
        // Use a transaction and FOR UPDATE lock to avoid races when multiple processes call this.
        return DB::transaction(function () {
            // Try to select the setting row with a lock
            $setting = DB::table('settings')
                ->where('ParameterName', '=', 'MAX_USER_ID')
                ->lockForUpdate()
                ->first();

            if ($setting && !is_null($setting->IntegerValue)) {
                $lastuser = (int)$setting->IntegerValue;
                $next = $lastuser + 1;

                // persist incremented value
                DB::table('settings')
                    ->where('ParameterName', '=', 'MAX_USER_ID')
                    ->update(['IntegerValue' => $next]);

                return $next;
            }

        });
    }


    public function createNewUser($mobile, $fullphone_number, $id_phone, $idUplineRegister, $status = StatusRequest::Registred->value)
    {
        $country = DB::table('countries')->where('phonecode', $id_phone)->first();
        $user = $this->initNewUser($status);
        $user->mobile = $mobile;
        $user->fullphone_number = $fullphone_number;
        $user->id_phone = $id_phone;
        $user->idCountry = $country->id;
        $user->idUplineRegister = $idUplineRegister;
        $user->save();

        // Keep MAX_USER_ID setting in sync with the newly created user id
        try {
            DB::table('settings')->updateOrInsert(
                ['ParameterName' => 'MAX_USER_ID'],
                ['IntegerValue' => $user->idUser]
            );
        } catch (\Exception $e) {
            // don't break user creation if settings update fails; log if needed
            // Log::error($e->getMessage());
        }

        return $user;
    }

    public function updateUser($user, $name, $mobile, $fullphone_number, $id_phone, $idUplineRegister)
    {
        $country = DB::table('countries')->where('phonecode', $id_phone)->first();
        $user->name = $name;
        $user->mobile = $mobile;
        $user->fullphone_number = $fullphone_number;
        $user->id_phone = $id_phone;
        $user->idCountry = $country->id;
        $user->idUplineRegister = $idUplineRegister;
        $user->save();
        return $user;
    }


    public function getUserByIdUser($idUser)
    {
        return User::where('idUser', $idUser)->first();
    }

    public function addSponsoring($upLine, $downLine)
    {
        $downLine->availablity = 1;
        $downLine->reserved_by = $upLine->idUser;
        $downLine->reserved_at = now();
        $downLine->save();
        return $downLine;
    }

    public function removeSponsoring($idUser, $reservation)
    {
        $user = User::where('idUser', $idUser)->first();
        $user->availablity = 1;
        $user->reserved_at = now()->modify('-' . $reservation . ' hours');
        $user->save();
        return $user;
    }

    public function updateUserUpline($idUser, $idUpline)
    {
        $user = User::where('idUser', $idUser)->first();
        if ($user) {
            $user->idUpline = $idUpline;
            $user->dateUpline = now();
            $user->save();
            return $user;
        }
        return NULL;
    }

    public function increasePurchasesNumber($idUser)
    {
        $user = User::where('idUser', $idUser)->first();
        if ($user) {
            $user->purchasesNumber = $user->purchasesNumber + 1;
            $user->save();
            return $user;
        }
        return NULL;

    }

    public function checkCanSponsorship($idUser, $reservation, $maxSponsorship)
    {
        $sponsorship = User::where('idUpline', 0)
            ->where('availablity', 1)
            ->where('reserved_by', $idUser)
            ->whereRaw('TIMESTAMPDIFF(HOUR, reserved_at, NOW()) < ' . $reservation)->count();
        return $sponsorship < $maxSponsorship ? true : false;

    }
}
