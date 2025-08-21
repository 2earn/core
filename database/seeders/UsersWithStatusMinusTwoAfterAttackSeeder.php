<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersWithStatusMinusTwoAfterAttackSeeder extends Seeder
{

    public function run(): void
    {
        User::where('status', -2)
            ->whereBetween('created_at', [
                Carbon::create(2025, 7, 29)->startOfDay(),
                Carbon::create(2025, 7, 30)->endOfDay()
            ])
            ->delete();

    }
}
