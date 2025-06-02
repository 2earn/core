<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComingSoonSeeder extends Seeder
{

    public function run()
    {
        $settings = [
            ['ParameterName' => 'hobbies', 'StringValue' => '2025/12/01'],
        ];

        foreach ($settings as $setting) {
            if (!DB::table('settings')->where("ParameterName", "=", $setting['ParameterName'] . '_cs')->exists()) {
                DB::table('settings')->insert([
                    'ParameterName' => $setting['ParameterName'] . '_cs',
                    'StringValue' => $setting['StringValue'],
                ]);
            }
        }
    }
}
