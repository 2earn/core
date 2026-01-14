<?php

namespace App\Services;

use App\Enums\LanguageEnum;
use App\Interfaces\IUserRepository;
use App\Models\metta_user;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MettaUserService
{
    public function __construct(
        private IUserRepository $userRepository
    )
    {
    }

    /**
     * Create a metta user record for the given user
     *
     * @param User $user
     * @return void
     */
    public function createMettaUser(User $user): void
    {
        $metta = new metta_user();
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
     * @return metta_user|null
     */
    public function createMettaUserByData(string $idUser, int $idLanguage, ?int $idCountry = null): ?metta_user
    {
        try {
            $metta = new metta_user();
            $metta->idUser = $idUser;
            $metta->idLanguage = $idLanguage;
            if ($idCountry) {
                $metta->idCountry = $idCountry;
            }

            $this->userRepository->createmettaUser($metta);
            return $metta;
        } catch (\Exception $e) {
            \Log::error('Error creating metta user by data: ' . $e->getMessage(), [
                'idUser' => $idUser,
                'idLanguage' => $idLanguage
            ]);
            return null;
        }
    }
}

