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
}

