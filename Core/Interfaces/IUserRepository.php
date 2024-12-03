<?php

namespace Core\Interfaces;


use App\Models\User;
use Core\Enum\BalanceEnum;
use Core\Models\metta_user;
use Core\Models\user_earn;
use Core\Models\UserContact;
interface IUserRepository
{
    public function createuser( User $user);
    public function getAllMettaUser();
    public function getAllUsersErans();
    public function getAllUsers();
    public function getUserContacts($idUser);
    public function addUserContact(UserContact $userContact);
    public function getUserContactsById($id);
    public function updateUserContact(UserContact $userContact);
    public function getUserById($id);
    public function getUserEarnByIdUser($iduser);
    public function getUserByMobile($mobile, $idContry,$pass);
    public function loginUser($user, $remenber);
    public function logoutUser();
    public function getUserByFullnumber($number);
    public function  getSoldeUserByAmount($idUser,BalanceEnum $amount);
    public function createmettaUser(metta_user $mettaUser);
    public function createUserEarn(user_earn $userEarn) ;
    public function getUserByMobileContry($mobile, $idContry);
    public function getConditionalMettaUser($attribute, $value);
    public function getConditionalUser($attribute, $value);


}
