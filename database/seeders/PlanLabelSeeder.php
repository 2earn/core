<?php

namespace Database\Seeders;

use App\Models\PlanLabel;
use Illuminate\Database\Seeder;

class PlanLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planLabels = [
            [
                'name' => 'Basic',
                'description' => 'Basic commission plan for new partners',
                'initial_commission' => 1.00,
                'final_commission' => 7.00,
                'step' => 6,
                'rate' => 0.50,
                'stars' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Modest',
                'description' => 'Modest commission plan for regular partners',
                'initial_commission' => 8.00,
                'final_commission' => 15.00,
                'step' => 7,
                'rate' => 1.00,
                'stars' => 1.00,
                'is_active' => true,
            ],
            [
                'name' => 'Standard',
                'description' => 'Standard commission plan for high-performing partners',
                'initial_commission' => 16.00,
                'final_commission' => 25.00,
                'step' => 9,
                'rate' => 2.00,
                'stars' => 2.00,
                'is_active' => true,
            ],
            [
                'name' => 'Good',
                'description' => 'Good commission plan for top-tier partners',
                'initial_commission' => 26.00,
                'final_commission' => 45.00,
                'step' => 19,
                'rate' => 3.00,
                'stars' => 3.00,
                'is_active' => true,
            ],
            [
                'name' => 'Wow',
                'description' => 'Wow commission plan for exclusive partners',
                'initial_commission' => 46.00,
                'final_commission' => 65.00,
                'step' => 19,
                'rate' => 4.00,
                'stars' => 4.00,
                'is_active' => true,
            ], [
                'name' => 'Magical',
                'description' => 'Magical commission plan for exclusive partners',
                'initial_commission' => 66.00,
                'final_commission' => 80.00,
                'step' => 14,
                'rate' => 4.50,
                'stars' => 4.00,
                'is_active' => true,
            ],
            [
                'name' => 'Unmissable',
                'description' => 'Unmissable commission plan for exclusive partners',
                'initial_commission' => 81.00,
                'final_commission' => 100.00,
                'step' => 19,
                'rate' => 5.00,
                'stars' => 5.00,
                'is_active' => true,
            ],
        ];

        foreach ($planLabels as $planLabel) {
            $planLabelModel = PlanLabel::create($planLabel);
            createTranslaleModel($planLabelModel, 'name', $planLabel['name']);
            createTranslaleModel($planLabelModel, 'description', $planLabel['description']);
        }

    }
}

