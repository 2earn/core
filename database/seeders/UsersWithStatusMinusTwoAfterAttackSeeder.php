<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UsersWithStatusMinusTwoAfterAttackSeeder extends Seeder
{

    public function run(): void
    {
        $number = User::where('status', -2)
            ->whereBetween('created_at', [
                Carbon::create(2025, 7, 29)->startOfDay(),
                Carbon::create(2025, 7, 30)->endOfDay()
            ])->count();
        Log::notice('user created from attack ' . $number);
        User::where('status', -2)
            ->whereBetween('created_at', [
                Carbon::create(2025, 7, 29)->startOfDay(),
                Carbon::create(2025, 7, 30)->endOfDay()
            ])
            ->delete();
        $number = User::where('status', -2)
            ->whereBetween('created_at', [
                Carbon::create(2025, 7, 29)->startOfDay(),
                Carbon::create(2025, 7, 30)->endOfDay()
            ])->count();
        Log::notice('user created from attack ' . $number);

    }
}
