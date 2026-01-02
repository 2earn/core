<?php

namespace Database\Seeders;

use App\Enums\PlatformType;
use App\Models\TranslaleModel;
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
                'description' => "2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Full->value,
                'link' => "2earn.cash",
                'business_sector_id' => 1
            ],
            [
                'name' => "learn2earn P",
                'description' => "learn2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "learn2earn.cash",
                'business_sector_id' => 2
            ],
            [
                'name' => "move2earn P",
                'description' => "move2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "move2earn.cash",
                'business_sector_id' => 3
            ], [
                'name' => "travel2earn P",
                'description' => "travel2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "travel2earn.cash",
                'business_sector_id' => 4
            ],
            [
                'name' => "shop2earn P",
                'description' => "shop2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "shop2earn.cash",
                'business_sector_id' => 5
            ], [
                'name' => "beelegant2earn P",
                'description' => "beelegant2earn P",
                'enabled' => true,
                'image_link' => $imageLink,
                'type' => PlatformType::Hybrid->value,
                'link' => "beelegant2earn.cash",
                'business_sector_id' => 6
            ],
            [
                'name' => "Speakenglish2earn P",
                'description' => "Speakenglish2earn P",
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
            $platformCreated = Platform::create($Platform);

            $translations = ['name', 'description'];
            foreach ($translations as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($platformCreated, $translation),
                        'value' => $platformCreated->{$translation} . ' AR',
                        'valueFr' => $platformCreated->{$translation} . ' FR',
                        'valueEn' => $platformCreated->{$translation} . ' EN',
                        'valueEs' => $platformCreated->{$translation} . ' ES',
                        'valueTr' => $platformCreated->{$translation} . ' TR',
                        'valueRu' => $platformCreated->{$translation} . ' Ru',
                        'valueDe' => $platformCreated->{$translation} . ' De',
                    ]);
            }
        }

    }
}
