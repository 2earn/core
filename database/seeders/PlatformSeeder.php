<?php

namespace Database\Seeders;

use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Vite;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageLink = Vite::asset('resources/images/logo-dark.png');
        $Platforms = [
            [
                'name' => "2earn",
                'description' => "2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Main->value,
                'link' => "2earn.cash"
            ],
            [
                'name' => "learn2earn",
                'description' => "learn2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Child->value,
                'link' => "learn2earn.cash"
            ],
            [
                'name' => "move2earn",
                'description' => "move2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Child->value,
                'link' => "move2earn.cash"
            ], [
                'name' => "travel2earn",
                'description' => "travel2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Child->value,
                'link' => "travel2earn.cash"
            ],
            [
                'name' => "shop2earn",
                'description' => "shop2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Child->value,
                'link' => "shop2earn.cash"
            ], [
                'name' => "beelegant2earn",
                'description' => "beelegant2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Child->value,
                'link' => "beelegant2earn.cash"
            ],
            [
                'name' => "Speakenglish2earn",
                'description' => "Speakenglish2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Partner->value,
                'link' => "Speakenglish2earn.cash"
            ],
        ];
        foreach ($Platforms as $Platform) {
            Platform::create($Platform);
        }

    }
}
