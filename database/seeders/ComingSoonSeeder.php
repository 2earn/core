<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ComingSoonSeeder extends Seeder
{

    public function run()
    {
        $settings = [
            ['ParameterName' => 'hobbies', 'StringValue' => '2025/12/01'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['ParameterName' => $setting['ParameterName'] . '_cs'],
                ['StringValue' => $setting['StringValue']]
            );
        }
    }
}
