<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Platform;

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

        // Update all platforms to have owner_id = 384
        $updatedCount = Platform::query()->update([
            'owner_id' => $userId
        ]);

        $this->command->info("Successfully updated {$updatedCount} platform(s) to have owner_id = {$userId}");
    }
}

