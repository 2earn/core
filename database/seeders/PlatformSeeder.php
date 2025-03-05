<?php

namespace Database\Seeders;

use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Vite;

class PlatformSeeder extends Seeder
{


    public function run()
    {
        $imageLink = Vite::asset('resources/images/logo-dark.png');
        $Platforms = [
            [
                'name' => "2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Full->value,
                'link' => "2earn.cash",
                'business_sector_id' => 1
            ],
            [
                'name' => "learn2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "learn2earn.cash",
                'business_sector_id' => 2
            ],
            [
                'name' => "move2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "move2earn.cash",
                'business_sector_id' => 3
            ], [
                'name' => "travel2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "travel2earn.cash",
                'business_sector_id' => 4
            ],
            [
                'name' => "shop2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "shop2earn.cash",
                'business_sector_id' => 5
            ], [
                'name' => "beelegant2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "beelegant2earn.cash",
                'business_sector_id' => 6
            ],
            [
                'name' => "Speakenglish2earn P",
                'description' => generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "Speakenglish2earn.cash",
                'business_sector_id' => 7
            ],
        ];

        if (Platform::all()->count()) {
            Platform::truncate();
        }

        foreach ($Platforms as $Platform) {
            Platform::create($Platform);
        }

    }
}
