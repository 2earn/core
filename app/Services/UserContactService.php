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
    public function getByUserIdWithSearch(int $userId, ?string $search = null): Collection
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
    public function setActiveNumber(int $userId, int $contactId): bool
    {
        // Deactivate all numbers for the user
        UserContactNumber::where('idUser', $userId)->update(['active' => 0]);

        // Activate the specified number
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
    public function contactNumberExists(int $userId, string $fullNumber): bool
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
    public function contactNumberExistsByMobile(int $userId, string $mobile, string $iso): bool
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
    public function createContactNumber(int $userId, string $mobile, int $countryId, string $iso, string $fullNumber): UserContactNumber
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
}

