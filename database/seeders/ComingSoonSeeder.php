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
            ['ParameterName' => 'job_opportunity', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'purchases_history', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'career_experience', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'hard_skills', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'soft_skills', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'personal_characterization', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'c_d_personality', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'representational_system', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'mbti', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'ebc', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'pdf', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'academic_background', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'sensory_rep_sys', 'StringValue' => '09/04/2025'],
            ['ParameterName' => 'generating_pdf', 'StringValue' => '09/04/2025'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert([
                'ParameterName' => $setting['ParameterName'] . '_cs',
                'StringValue' => $setting['StringValue'],
            ]);
        }
    }
}
