<?php

namespace Database\Seeders;

use App\Models\UserGuide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        for ($i = 1; $i <= 15; $i++) {
            UserGuide::create([
                'title' => 'Guide Title ' . $i,
                'description' => 'This is a description for guide ' . $i . '. ' . Str::random(40),
                'file_path' => 'guides/sample' . $i . '.pdf',
                'user_id' => $userIds[array_rand($userIds)],
            ]);
        }
    }
}
