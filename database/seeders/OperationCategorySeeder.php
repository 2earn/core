<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationCategorySeeder extends Seeder
{
    const TABLE_NAME = 'operation_categories';

    public function run(): void
    {
        DB::table(self::TABLE_NAME)->truncate();

        DB::table(self::TABLE_NAME)->insert([
            ['code' => 'CAT001', 'name' => 'signup', 'description' => 'User signup operation'],
            ['code' => 'CAT002', 'name' => 'identification', 'description' => 'User identification operation'],
            ['code' => 'CAT003', 'name' => 'purchase', 'description' => 'Purchase operation'],
            ['code' => 'CAT005', 'name' => 'transfer', 'description' => 'Transfer operation'],
            ['code' => 'CAT006', 'name' => 'commissions', 'description' => 'Commission operation'],
            ['code' => 'CAT007', 'name' => 'sponsorship', 'description' => 'Sponsorship operation'],
            ['code' => 'CAT008', 'name' => 'dealrush', 'description' => 'Dealrush operation'],
            ['code' => 'CAT009', 'name' => 'marketing', 'description' => 'Marketing operation'],
            ['code' => 'CAT010', 'name' => 'archivated', 'description' => 'Archived operation'],
            ['code' => 'CAT011', 'name' => 'sale and consumption', 'description' => 'Sale and consumption operation'],
            ['code' => 'CAT012', 'name' => 'recharge', 'description' => 'Recharge operation'],
        ]);
    }
}
