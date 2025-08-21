<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersWithStatusMinusTwoAfterAttackSeeder extends Seeder
{

    public function run(): void
    {
        User::where('status', -2)
            ->whereBetween('created_at', ['2025-07-29', '2025-07-30'])
            ->delete();
    }
}
