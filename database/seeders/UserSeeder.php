<?php

namespace Database\Seeders;

use App\Services\UserService;
use App\Services\MettaUsersService;
use App\Services\UserContactNumberService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function __construct(
        private UserService $userService,
        private MettaUsersService $mettaUsersService,
        private UserContactNumberService $userContactNumberService
    ) {}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->userService->createUser([
            'name' => "admin2earn",
            'email' => 'admin@2earn.com',
            'password' => Hash::make('admin2earn'),
            'email_verified_at' => now(),
            'idUser' => '999999999',
            'mobile' => '99999999',
            'fullphone_number' => '0021699999999',
            'status' => 1,
            'idCountry' => 222,
            'is_public' => 1,
            'typeProfil' => 1,
            'id_phone' => 216,
            'registred_from' => 3
        ]);

        $this->mettaUsersService->createMettaUser([
            'idUser' => '999999999',
            'idLanguage' => '1'
        ]);

        $this->userContactNumberService->createUserContactNumber(
            idUser: '999999999',
            mobile: '99999999',
            codeP: 216,
            iso: 'tn',
            fullNumber: '0021699999999'
        );
    }
}
