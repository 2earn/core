<?php

namespace App\Services;

use App\Enums\LanguageEnum;
use App\Interfaces\IUserRepository;
use App\Models\MettaUser;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        foreach (LanguageEnum::cases() as $lanque) {
            if ($lanque->name == $countrie_earn->langage) {
                $metta->idLanguage = $lanque->value;
                break;
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
}


