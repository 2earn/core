<?php

namespace App\Services;

use App\Models\ContactUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ContactUserService
{
    /**
     * Find contact user by ID and user ID
     *
     * @param int $contactId
     * @param string $userId
     * @return ContactUser|null
     */
    public function findByIdAndUserId(int $contactId, string $userId): ?ContactUser
    {
        try {
            return ContactUser::where('id', $contactId)
                ->where('idUser', $userId)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding contact user: ' . $e->getMessage(), [
                'contactId' => $contactId,
                'userId' => $userId
            ]);
            return null;
        }
    }

    /**
     * Find contact user by user ID and full phone number
     *
     * @param string $userId
     * @param string $fullPhoneNumber
     * @return ContactUser|null
     */
    public function findByUserIdAndFullPhone(string $userId, string $fullPhoneNumber): ?ContactUser
    {
        try {
            return ContactUser::where('idUser', $userId)
                ->where('fullphone_number', $fullPhoneNumber)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding contact by full phone: ' . $e->getMessage(), [
                'userId' => $userId,
                'fullPhoneNumber' => $fullPhoneNumber
            ]);
            return null;
        }
    }

    /**
     * Find contact user by user ID, mobile, and phone code
     *
     * @param string $userId
     * @param string $mobile
     * @param string $phoneCode
     * @return ContactUser|null
     */
    public function findByUserIdMobileAndCode(string $userId, string $mobile, string $phoneCode): ?ContactUser
    {
        try {
            return ContactUser::where('idUser', $userId)
                ->where('mobile', $mobile)
                ->where('phonecode', $phoneCode)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding contact by mobile and code: ' . $e->getMessage(), [
                'userId' => $userId,
                'mobile' => $mobile,
                'phoneCode' => $phoneCode
            ]);
            return null;
        }
    }

    /**
     * Get all contacts for a user
     *
     * @param string $userId
     * @return Collection
     */
    public function getByUserId(string $userId): Collection
    {
        try {
            return ContactUser::where('idUser', $userId)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching contacts by user ID: ' . $e->getMessage(), ['userId' => $userId]);
            return new Collection();
        }
    }

    /**
     * Create a contact user
     *
     * @param array $data
     * @return ContactUser|null
     */
    public function create(array $data): ?ContactUser
    {
        try {
            return ContactUser::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating contact user: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Update a contact user
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $contact = ContactUser::findOrFail($id);
            return $contact->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating contact user: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Delete a contact user
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $contact = ContactUser::findOrFail($id);
            return $contact->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting contact user: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }
}

