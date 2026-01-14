<?php

namespace Database\Seeders;

use App\Services\ContactUserService;
use App\Services\MettaUsersService;
use App\Services\UserContactNumberService;
use App\Services\UserService;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class addContactUserSeeder extends Seeder
{
    public function __construct(
        private UserService $userService,
        private MettaUsersService $mettaUsersService,
        private UserContactNumberService $userContactNumberService,
        private ContactUserService $contactUserService
    ) {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        for ($i = 1; $i <= 2; $i++) {
            {
                try {
                    $this->insertContactUser($faker);
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }
            }
        }
    }

    public function insertContactUser($faker)
    {
        $name = $faker->word();
        $names = explode(" ", $name);
        $num = $faker->randomNumber(5, true);
        $phone = 22900000 + $num;
        $idUser = 999900000 + $num;
        $fullPhoneNumber = '00216' . $phone;

        // Create user using UserService
        $this->userService->createUser([
            'name' => $name,
            'email' => str_replace(' ', '', strtolower($name)) . '@2earn.com',
            'password' => Hash::make($name),
            'email_verified_at' => now(),
            'idUser' => $idUser,
            'mobile' => $phone,
            'fullphone_number' => $fullPhoneNumber,
            'status' => 1,
            'idCountry' => 222,
            'is_public' => 1,
            'typeProfil' => 1,
            'id_phone' => 216,
            'registred_from' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create metta user using MettaUserService
        $this->mettaUserService->createMettaUserByData(
            idUser: (string)$idUser,
            idLanguage: 1,
            idCountry: 222
        );

        // Create user contact number using UserContactNumberService
        $this->userContactNumberService->createUserContactNumber(
            idUser: (string)$idUser,
            mobile: (string)$phone,
            codeP: 216,
            iso: 'tn',
            fullNumber: $fullPhoneNumber
        );

        // Create contact user using ContactUserService
        $this->contactUserService->create([
            'name' => $names[0],
            'lastName' => $names[1] ?? '',
            'idUser' => '197604325',
            'idContact' => $idUser,
            'mobile' => $phone,
            'fullphone_number' => $fullPhoneNumber,
            'phonecode' => 216,
            'disponible' => 1,
            'availablity' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
