<?php

namespace App\Services;

use App\Models\UserContactNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserContactNumberService
{
    /**
     * Find contact number by mobile, iso, and user
     *
     * @param string $mobile
     * @param string $iso
     * @param string $idUser
     * @return UserContactNumber|null
     */
    public function findByMobileAndIsoForUser(string $mobile, string $iso, string $idUser): ?UserContactNumber
    {
        try {
            return UserContactNumber::where('mobile', $mobile)
                ->where('isoP', $iso)
                ->where('idUser', $idUser)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding contact number: ' . $e->getMessage(), [
                'mobile' => $mobile,
                'iso' => $iso,
                'idUser' => $idUser
            ]);
            return null;
        }
    }

    /**
     * Deactivate all contact numbers for a user
     *
     * @param string $idUser
     * @return int Number of rows updated
     */
    public function deactivateAllForUser(string $idUser): int
    {
        try {
            return DB::update('update usercontactnumber set active = 0, isID = 0 where idUser = ?', [$idUser]);
        } catch (\Exception $e) {
            Log::error('Error deactivating contact numbers: ' . $e->getMessage(), ['idUser' => $idUser]);
            return 0;
        }
    }

    /**
     * Set a contact number as active and primary for a user
     *
     * @param int $contactId
     * @return int Number of rows updated
     */
    public function setAsActiveAndPrimary(int $contactId): int
    {
        try {
            return DB::update('update usercontactnumber set active = ?, isID = 1 where id = ?', [1, $contactId]);
        } catch (\Exception $e) {
            Log::error('Error setting contact as active: ' . $e->getMessage(), ['contactId' => $contactId]);
            return 0;
        }
    }

    /**
     * Update existing contact number and set as active/primary
     *
     * @param int $contactId
     * @param string $idUser
     * @return bool
     */
    public function updateAndActivate(int $contactId, string $idUser): bool
    {
        try {
            DB::beginTransaction();

            // Deactivate all
            $this->deactivateAllForUser($idUser);

            // Activate specific one
            $this->setAsActiveAndPrimary($contactId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating and activating contact: ' . $e->getMessage(), [
                'contactId' => $contactId,
                'idUser' => $idUser
            ]);
            return false;
        }
    }

    /**
     * Create new contact number and set as active/primary
     *
     * @param int $newContactId
     * @param string $idUser
     * @return bool
     */
    public function createAndActivate(int $newContactId, string $idUser): bool
    {
        try {
            DB::beginTransaction();

            // Deactivate all
            $this->deactivateAllForUser($idUser);

            // Activate the new one
            $this->setAsActiveAndPrimary($newContactId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating and activating contact: ' . $e->getMessage(), [
                'newContactId' => $newContactId,
                'idUser' => $idUser
            ]);
            return false;
        }
    }

    /**
     * Create a new user contact number
     *
     * @param string $idUser
     * @param string $mobile
     * @param int $codeP
     * @param string $iso
     * @param string $fullNumber
     * @return UserContactNumber
     */
    public function createUserContactNumber(string $idUser, string $mobile, int $codeP, string $iso, string $fullNumber): UserContactNumber
    {
        return UserContactNumber::create([
            'idUser' => $idUser,
            'mobile' => $mobile,
            'codeP' => $codeP,
            'active' => 1,
            'isoP' => strtolower($iso),
            'isID' => true,
            'fullNumber' => $fullNumber,
        ]);
    }

    /**
     * Update user contact number for a user
     *
     * @param string $idUser
     * @param string $mobile
     * @param int $codeP
     * @param string $iso
     * @param string $fullNumber
     * @return bool
     */
    public function updateUserContactNumber(string $idUser, string $mobile, int $codeP, string $iso, string $fullNumber): bool
    {
        try {
            $userContactNumbers = UserContactNumber::where('idUser', $idUser)->get();
            if ($userContactNumbers->isNotEmpty()) {
                foreach ($userContactNumbers as $userContactNumber) {
                    $userContactNumber->update([
                        'idUser' => $idUser,
                        'mobile' => $mobile,
                        'codeP' => $codeP,
                        'active' => 1,
                        'isoP' => $iso,
                        'isID' => true,
                        'fullNumber' => $fullNumber,
                    ]);
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error updating user contact number: ' . $e->getMessage(), ['idUser' => $idUser]);
            return false;
        }
    }

    /**
     * Create a user contact number by properties
     *
     * @param string $idUser
     * @param string $mobile
     * @param int $idCountry
     * @param string $iso
     * @param string $fullNumber
     * @return UserContactNumber
     */
    public function createUserContactNumberByProp(string $idUser, string $mobile, int $idCountry, string $iso, string $fullNumber): UserContactNumber
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
}

