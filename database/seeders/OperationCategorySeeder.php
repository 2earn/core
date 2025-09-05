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
            ['code' => 'CAT001', 'name' => 'signup', 'description' => 'User signup operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT002', 'name' => 'identification', 'description' => 'User identification operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT003', 'name' => 'purchase', 'description' => 'Purchase operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT005', 'name' => 'transfer', 'description' => 'Transfer operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT006', 'name' => 'commissions', 'description' => 'Commission operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT007', 'name' => 'sponsorship', 'description' => 'Sponsorship operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT008', 'name' => 'dealrush', 'description' => 'Dealrush operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT009', 'name' => 'marketing', 'description' => 'Marketing operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT010', 'name' => 'archivated', 'description' => 'Archived operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT011', 'name' => 'sale and consumption', 'description' => 'Sale and consumption operation', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAT012', 'name' => 'recharge', 'description' => 'Recharge operation', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
