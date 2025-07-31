<?php

namespace Database\Seeders;

use App\Models\OperationCategory;
use Illuminate\Database\Seeder;

class OperationCategorySeeder extends Seeder
{

    public function run(): void
    {
        $operationCategories = [
            'signup',
            'identification',
            'purchase',
            'activities',
            'transfer',
            'commissions',
            'sponsorship',
            'deal rush',
            'marketing',
            'archived',
            'sale and consumption',
            'recharge',
        ];
        foreach ($operationCategories as $operationCategory) {
            $op = OperationCategory::where('name', $operationCategory)->first();
            if (is_null($op))
                OperationCategory::create([
                    'name' => $operationCategory
                ]);
        }
    }
}
