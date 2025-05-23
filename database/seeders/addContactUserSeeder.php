<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class addContactUserSeeder extends Seeder
{
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

        DB::table('users')->insert([
            'name' => $name,
            'email' => str_replace(' ', '', strtolower($name)) . '@2earn.com',
            'password' => Hash::make($name),
            'email_verified_at' => now(),
            'idUser' => 999900000 + $num,
            'mobile' => $phone,
            'fullphone_number' => '00216' . $phone,
            'status' => 1,
            'idCountry' => 222,
            'is_public' => 1,
            'typeProfil' => 1,
            'id_phone' => 216,
            'registred_from' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('metta_users')->insert([
            'idUser' => '9999' . $num,
            'idLanguage' => '1'
        ]);

        DB::table('usercontactnumber')->insert([
            'mobile' => $phone,
            'idUser' => 999900000 + $num,
            'active' => '1',
            'isID' => '1',
            'isoP' => 'tn',
            'fullNumber' => '00216' . $phone
        ]);


        DB::table('contact_users')->insert([
            'name' => $names[0],
            'lastName' => $names[1],
            'idUser' => '197604325',
            'idContact' => 999900000 + $num,
            'mobile' => $phone,
            'fullphone_number' => '00216' . $phone,
            'phonecode' => 216,
            'disponible' => 1,
            'availablity' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
