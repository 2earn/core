<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Platform;
use App\Models\EntityRole;

class UpdatePlatformOwnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = 384;

        // Get all platforms
        $platforms = Platform::all();
        $createdCount = 0;

        foreach ($platforms as $platform) {
            // Create or update owner role for each platform
            $role = EntityRole::updateOrCreate(
                [
                    'user_id' => $userId,
                    'roleable_type' => Platform::class,
                    'roleable_id' => $platform->id,
                    'name' => 'owner'
                ],
                [
                    'created_by' => $userId,
                    'updated_by' => $userId
                ]
            );

            if ($role->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        $this->command->info("Successfully assigned owner role to user {$userId} for {$createdCount} platform(s)");
        $this->command->info("Total platforms processed: {$platforms->count()}");
    }
}

