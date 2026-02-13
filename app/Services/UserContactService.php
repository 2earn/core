<?php

namespace App\Services;

use App\Models\UserContactNumber;
use Illuminate\Support\Collection;

class UserContactService
{
    /**
     * Get user contact numbers by user ID with optional search filter.
     *
     * @param int $userId
     * @param string|null $search
     * @return Collection
     */
    public function getByUserIdWithSearch(string $userId, ?string $search = null): Collection
    {
        $query = UserContactNumber::select(
            'id',
            'idUser',
            'mobile',
            'codeP',
            'active',
            'isoP',
            'fullNumber',
            'isID'
        )
            ->where('idUser', $userId);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('mobile', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        return $query->get();
    }

    /**
     * Set active number for a user.
     *
     * @param int $userId
     * @param int $contactId
     * @return bool
     */
    public function setActiveNumber(string $userId, int $contactId): bool
    {
        UserContactNumber::where('idUser', $userId)->update(['active' => 0]);

        return UserContactNumber::where('id', $contactId)
            ->where('idUser', $userId)
            ->update(['active' => 1]) > 0;
    }

    /**
     * Delete a contact number.
     *
     * @param int $contactId
     * @return bool
     * @throws \Exception
     */
    public function deleteContact(int $contactId): bool
    {
        $number = UserContactNumber::find($contactId);

        if (!$number) {
            throw new \Exception('Contact number not found');
        }

        if ($number->active) {
            throw new \Exception('Failed to delete active number');
        }

        if ($number->isID == 1) {
            throw new \Exception('Contact number deleting failed');
        }

        return $number->delete();
    }

    /**
     * Check if a contact number already exists for a user.
     *
     * @param int $userId
     * @param string $fullNumber
     * @return bool
     */
    public function contactNumberExists(string $userId, string $fullNumber): bool
    {
        return UserContactNumber::where('idUser', $userId)
            ->where('fullNumber', $fullNumber)
            ->exists();
    }

    /**
     * Check if a contact number exists by mobile and country code.
     *
     * @param int $userId
     * @param string $mobile
     * @param string $iso
     * @return bool
     */
    public function contactNumberExistsByMobile(string $userId, string $mobile, string $iso): bool
    {
        return UserContactNumber::where('idUser', $userId)
            ->where('mobile', $mobile)
            ->where('isoP', $iso)
            ->exists();
    }

    /**
     * Create a new user contact number.
     *
     * @param int $userId
     * @param string $mobile
     * @param int $countryId
     * @param string $iso
     * @param string $fullNumber
     * @return UserContactNumber
     */
    public function createContactNumber(string $userId, string $mobile, int $countryId, string $iso, string $fullNumber): UserContactNumber
    {
        return UserContactNumber::create([
            'idUser' => $userId,
            'mobile' => $mobile,
            'codeP' => $countryId,
            'isoP' => $iso,
            'fullNumber' => $fullNumber,
            'active' => 0,
            'isID' => 0,
        ]);
    }

    /**
     * Prepare contact number verification by generating OTP and checking if number exists
     *
     * @param string $idUser User's business ID
     * @param int $userId User ID
     * @param string $fullNumber Full phone number with country code
     * @param string $isoP Country ISO code
     * @param string $mobile Mobile number without country code
     * @param string|null $userEmail User's email for notification
     * @param string $idNumberFullNumber ID contact number for SMS
     * @return array Result array with success status, message, and verification params
     */
    public function prepareContactNumberVerification(
        string $idUser,
        string $userId,
        string $fullNumber,
        string $isoP,
        string $mobile,
        ?string $userEmail,
        string $idNumberFullNumber
    ): array 
    {
        try {
            // Check if contact number already exists
            if ($this->contactNumberExists($idUser, $fullNumber)) {
                return [
                    'success' => false,
                    'message' => 'This contact number already exists',
                    'alertType' => 'danger'
                ];
            }

            // Generate OTP code
            $otpCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Prepare message based on email availability
            $msgSend = \Illuminate\Support\Facades\Lang::get('We_will_sendWithMail');
            if (empty($userEmail)) {
                $msgSend = \Illuminate\Support\Facades\Lang::get('We_will_send');
            }

            return [
                'success' => true,
                'otpCode' => $otpCode,
                'verificationParams' => [
                    'type' => 'warning',
                    'title' => 'Opt',
                    'text' => '',
                    'FullNumber' => $idNumberFullNumber,
                    'FullNumberNew' => $fullNumber,
                    'userMail' => $userEmail,
                    'isoP' => $isoP,
                    'mobile' => $mobile,
                    'msgSend' => $msgSend
                ],
                'shouldNotifyBySms' => true,
                'shouldNotifyByEmail' => !empty($userEmail)
            ];
            
        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error('Error preparing contact number verification: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error preparing contact verification',
                'alertType' => 'danger'
            ];
        }
    }

    /**
     * Verify OTP code and save new contact number
     *
     * @param string $userId User ID
     * @param string $idUser User's business ID
     * @param string $code OTP code to verify
     * @param string $storedOtp Stored OTP code from user
     * @param string $mobile Mobile number without country code
     * @param int $countryId Country ID
     * @param string $iso Country ISO code
     * @param string $fullNumber Full phone number with country code
     * @return array Result array with success status and message
     */
    public function verifyAndSaveContactNumber(
        string $userId,
        string $idUser,
        string $code,
        string $storedOtp,
        string $mobile,
        int $countryId,
        string $iso,
        string $fullNumber
    ): array 
    {
        try {
            // Verify OTP code
            if ($code != $storedOtp) {
                return [
                    'success' => false,
                    'message' => 'Invalid OPT code'
                ];
            }

            // Create new contact number
            $this->createContactNumber($idUser, $mobile, $countryId, $iso, $fullNumber);

            return [
                'success' => true,
                'message' => 'Adding contact number completed successfully'
            ];

        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\Log::error('Error saving contact number: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error saving contact number'
            ];
        }
    }
}

