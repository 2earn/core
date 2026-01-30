<?php

namespace App\Services;

use App\Enums\LanguageEnum;
use App\Interfaces\IUserRepository;
use App\Models\MettaUser;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MettaUsersService
{
    public function __construct(
        private IUserRepository $userRepository
    )
    {
    }
    /**
     * Get metta user info by user ID
     *
     * @param int $userId
     * @return Collection
     */
    public function getMettaUserInfo(int $userId): Collection
    {
        try {
            $mettaUser = MettaUser::where('idUser', $userId)->first();
            return collect($mettaUser);
        } catch (\Exception $e) {
            Log::error('Error fetching metta user info', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return collect();
        }
    }

    /**
     * Get metta user by user ID
     *
     * @param int $userId
     * @return MettaUser|null
     */
    public function getMettaUser(int $userId): ?MettaUser
    {
        try {
            return MettaUser::where('idUser', $userId)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching metta user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get metta user full name
     *
     * @param int $userId
     * @return string
     */
    public function getMettaUserFullName(int $userId): string
    {
        try {
            $mettaUser = $this->getMettaUser($userId);

            if (!$mettaUser) {
                return '';
            }

            $firstName = $mettaUser->enFirstName ?? '';
            $lastName = $mettaUser->enLastName ?? '';

            return trim($firstName . ' ' . $lastName);
        } catch (\Exception $e) {
            Log::error('Error fetching metta user full name', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Check if metta user exists
     *
     * @param int $userId
     * @return bool
     */
    public function mettaUserExists(int $userId): bool
    {
        try {
            return MettaUser::where('idUser', $userId)->exists();
        } catch (\Exception $e) {
            Log::error('Error checking metta user existence', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Create a new metta user from array data
     *
     * @param array $data
     * @return MettaUser|null
     */
    public function createMettaUser(array $data): ?MettaUser
    {
        try {
            return MettaUser::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating metta user', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create a metta user record for the given user
     *
     * @param User $user
     * @return void
     */
    public function createMettaUserFromUser(User $user): void
    {
        $metta = new MettaUser();
        $metta->idUser = $user->idUser;
        $metta->idCountry = $user->idCountry;

        $countrie_earn = DB::table('countries')->where('phonecode', $user->id_phone)->first();

        if ($countrie_earn) {
            foreach (LanguageEnum::cases() as $lanque) {
                if ($lanque->name == $countrie_earn->langage) {
                    $metta->idLanguage = $lanque->value;
                    break;
                }
            }
        }

        $this->userRepository->createmettaUser($metta);
    }

    /**
     * Create a metta user record with raw data
     *
     * @param string $idUser
     * @param int $idLanguage
     * @param int|null $idCountry
     * @return MettaUser|null
     */
    public function createMettaUserByData(string $idUser, int $idLanguage, ?int $idCountry = null): ?MettaUser
    {
        try {
            $metta = new MettaUser();
            $metta->idUser = $idUser;
            $metta->idLanguage = $idLanguage;
            if ($idCountry) {
                $metta->idCountry = $idCountry;
            }

            $this->userRepository->createmettaUser($metta);
            return $metta;
        } catch (\Exception $e) {
            Log::error('Error creating metta user by data: ' . $e->getMessage(), [
                'idUser' => $idUser,
                'idLanguage' => $idLanguage
            ]);
            return null;
        }
    }

    /**
     * Update metta user
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateMettaUser(int $userId, array $data): bool
    {
        try {
            $mettaUser = MettaUser::where('idUser', $userId)->first();

            if (!$mettaUser) {
                Log::warning('Metta user not found for update', ['userId' => $userId]);
                return false;
            }

            return $mettaUser->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating metta user', [
                'userId' => $userId,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update metta user profile information and handle ID images upload
     *
     * @param int $mettaUserId The metta user ID
     * @param array $data The data to update (enFirstName, enLastName, birthday, nationalID)
     * @param TemporaryUploadedFile|null $photoFront Front ID image
     * @param TemporaryUploadedFile|null $photoBack Back ID image
     * @return array ['success' => bool, 'message' => string, 'type' => string]
     * @throws \Exception
     */
    public function updateProfileWithImages(
        int $mettaUserId,
        array $data,
        $photoFront = null,
        $photoBack = null
    ): array
    {
        try {
            $mettaUser = MettaUser::find($mettaUserId);

            if (!$mettaUser) {
                return [
                    'success' => false,
                    'message' => 'Metta user not found',
                    'type' => 'danger'
                ];
            }

            // Update metta user data
            if (isset($data['enLastName'])) {
                $mettaUser->enLastName = $data['enLastName'];
            }

            if (isset($data['enFirstName'])) {
                $mettaUser->enFirstName = $data['enFirstName'];
            }

            if (isset($data['birthday'])) {
                $mettaUser->birthday = $data['birthday'];
            }

            if (isset($data['nationalID'])) {
                $mettaUser->nationalID = $data['nationalID'];
            }

            $mettaUser->save();

            // Handle front ID image upload
            if (!is_null($photoFront) && is_object($photoFront)) {
                $photoFront->storeAs('profiles', 'front-id-image' . $mettaUser->idUser . '.png', 'public2');
            }

            // Handle back ID image upload
            if (!is_null($photoBack) && is_object($photoBack)) {
                $photoBack->storeAs('profiles', 'back-id-image' . $mettaUser->idUser . '.png', 'public2');
            }

            return [
                'success' => true,
                'message' => 'Edit profile success',
                'type' => 'success'
            ];

        } catch (\Exception $e) {
            Log::error('Error updating metta user profile', [
                'mettaUserId' => $mettaUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'type' => 'danger'
            ];
        }
    }

    /**
     * Calculate profile completeness percentage and validation errors
     *
     * @param Collection $mettaUserInfo Metta user information
     * @param array|Collection $userInfo User information (must have 'email' key)
     * @param string $idUser User's business ID
     * @return array ['percentComplete' => int, 'errors' => array]
     */
    public function calculateProfileCompleteness($mettaUserInfo, $userInfo, string $idUser): array
    {
        $errors = [];
        $percentComplete = 0;

        // Check first and last names (20%)
        if (isset($mettaUserInfo['enFirstName']) && trim($mettaUserInfo['enFirstName']) != ""
            && isset($mettaUserInfo['enLastName']) && trim($mettaUserInfo['enLastName']) != "") {
            $percentComplete += 20;
        }

        if (!isset($mettaUserInfo['enFirstName']) || trim($mettaUserInfo['enFirstName']) == "") {
            $errors[] = getProfileMsgErreur('enFirstName');
        }
        if (!isset($mettaUserInfo['enLastName']) || trim($mettaUserInfo['enLastName']) == "") {
            $errors[] = getProfileMsgErreur('enLastName');
        }

        // Check birthday (20%)
        if (isset($mettaUserInfo['birthday'])) {
            $percentComplete += 20;
        } else {
            $errors[] = getProfileMsgErreur('birthday');
        }

        // Check national ID (20%)
        if (isset($mettaUserInfo['nationalID']) && trim($mettaUserInfo['nationalID']) != "") {
            $percentComplete += 20;
        } else {
            $errors[] = getProfileMsgErreur('nationalID');
        }

        // Check national ID images (20%)
        if (User::getNationalFrontImage($idUser) != User::DEFAULT_NATIONAL_FRONT_URL
            && User::getNationalBackImage($idUser) != User::DEFAULT_NATIONAL_BACK_URL) {
            $percentComplete += 20;
        } else {
            $errors[] = getProfileMsgErreur('photoIdentite');
        }

        // Check email (20%)
        $email = is_array($userInfo) ? ($userInfo['email'] ?? null) : $userInfo->get('email');
        if (isset($email) && trim($email) != "") {
            $percentComplete += 20;
        } else {
            $errors[] = getProfileMsgErreur('email');
        }

        return [
            'percentComplete' => $percentComplete,
            'errors' => $errors
        ];
    }
}


