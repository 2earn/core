<?php

namespace Database\Seeders;

use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Vite;

class PlatformSeeder extends Seeder
{
    public function generateRandomWord($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomWord = '';

        for ($i = 0; $i < $length; $i++) {
            $randomWord .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomWord;
    }

    public function generateRandomText($wordCount, $wordLengthRange = [3, 10])
    {
        $randomText = '';

        for ($i = 0; $i < $wordCount; $i++) {
            $wordLength = rand($wordLengthRange[0], $wordLengthRange[1]);
            $randomText .= $this->generateRandomWord($wordLength) . ' ';
        }

        return trim($randomText);
    }

    public function run()
    {
        $imageLink = Vite::asset('resources/images/logo-dark.png');
        $Platforms = [
            [
                'name' => "2earn P",
                'description' => $this->generateRandomText(500),
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Full->value,
                'link' => "2earn.cash",
                'business_sector_id' => 1
            ],
            [
                'name' => "learn2earn P",
                'description' => "learn2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "learn2earn.cash",
                'business_sector_id' => 2
            ],
            [
                'name' => "move2earn P",
                'description' => "move2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "move2earn.cash",
                'business_sector_id' => 3
            ], [
                'name' => "travel2earn P",
                'description' => "travel2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "travel2earn.cash",
                'business_sector_id' => 4
            ],
            [
                'name' => "shop2earn P",
                'description' => "shop2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "shop2earn.cash",
                'business_sector_id' => 5
            ], [
                'name' => "beelegant2earn P",
                'description' => "beelegant2earn",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "beelegant2earn.cash",
                'business_sector_id' => 6
            ],
            [
                'name' => "Speakenglish2earn P",
                'description' => "Speakenglish2earn",
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
