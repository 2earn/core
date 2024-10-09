<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComingSoonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['ParameterName' => 'job_opportunity', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'purchases_history', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'career_experience', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'hard_skills', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'soft_skills', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'personal_characterization', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'c_d_personality', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'representational_system', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'mbti', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'ebc', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'pdf', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'academic_background', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'sensory_rep_sys', 'StringValue' => '2025/04/07'],
            ['ParameterName' => 'generating_pdf', 'StringValue' => '2025/04/07'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert([
                'ParameterName' => $setting['ParameterName'] . '_cs',
                'StringValue' => $setting['StringValue'],
            ]);
        }
    }
}
