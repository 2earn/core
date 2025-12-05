<?php

namespace Database\Seeders;

use App\Models\CommissionFormula;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionFormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formulas = [
            [
                'name' => 'Starter Commission Plan',
                'description' => 'Basic commission plan for new partners',
                'initial_commission' => 5.00,
                'final_commission' => 10.00,
                'is_active' => true,
            ],
            [
                'name' => 'Standard Commission Plan',
                'description' => 'Standard commission plan for regular partners',
                'initial_commission' => 8.00,
                'final_commission' => 15.00,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Commission Plan',
                'description' => 'Premium commission plan for high-performing partners',
                'initial_commission' => 12.00,
                'final_commission' => 20.00,
                'is_active' => true,
            ],
            [
                'name' => 'Elite Commission Plan',
                'description' => 'Elite commission plan for top-tier partners',
                'initial_commission' => 15.00,
                'final_commission' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => 'VIP Commission Plan',
                'description' => 'VIP commission plan for exclusive partners',
                'initial_commission' => 20.00,
                'final_commission' => 30.00,
                'is_active' => true,
            ],
        ];

        foreach ($formulas as $formula) {
            CommissionFormula::create($formula);
        }

    }
}

