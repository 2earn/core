<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('users')->insert([
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

        DB::table('metta_users')->insert([
            'idUser' => '999999999',
            'idLanguage' => '1'
        ]);

        DB::table('usercontactnumber')->insert([
            'mobile' => '99999999',
            'idUser' => '999999999',
            'active' => '1',
            'isID' => '1',
            'isoP' => 'tn',
            'fullNumber' => '0021699999999'
        ]);

    }
}
