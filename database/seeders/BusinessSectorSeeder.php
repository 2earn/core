<?php

namespace Database\Seeders;

use App\Models\BusinessSector;
use App\Models\TranslaleModel;
use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Vite;

class BusinessSectorSeeder extends Seeder
{

    public function run()
    {
        $BusinessSectors = [
            [
                'name' => "2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "learn2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "move2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "travel2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "shop2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "beelegant2earn BS",
                'description' => generateRandomText(200),
            ],
            [
                'name' => "Speakenglish2earn BS",
                'description' => generateRandomText(200),
            ],
        ];

        if (BusinessSector::all()->count()) {
            BusinessSector::truncate();
        }

        foreach ($BusinessSectors as $sector) {
            $businessSectorCreated = BusinessSector::create($sector);

            $translations = ['name', 'description'];
            foreach ($translations as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($businessSectorCreated, $translation),
                        'value' => $businessSectorCreated->{$translation} . ' AR',
                        'valueFr' => $businessSectorCreated->{$translation} . ' FR',
                        'valueEn' => $businessSectorCreated->{$translation} . ' EN',
                        'valueEs' => $businessSectorCreated->{$translation} . ' ES',
                        'valueTr' => $businessSectorCreated->{$translation} . ' TR',
                        'valueRu' => $businessSectorCreated->{$translation} . ' Ru',
                        'valueDe' => $businessSectorCreated->{$translation} . ' De',
                    ]);
            }
        }
    }
}
