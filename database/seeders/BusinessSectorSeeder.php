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
                'description' => "2earn BS",
            ],
            [
                'name' => "learn2earn BS",
                'description' => "learn2earn BS",
            ],
            [
                'name' => "move2earn BS",
                'description' => "move2earn BS",
            ],
            [
                'name' => "travel2earn BS",
                'description' => "travel2earn BS",
            ],
            [
                'name' => "shop2earn BS",
                'description' => "shop2earn BS",
            ],
            [
                'name' => "beelegant2earn BS",
                'description' => "beelegant2earn BS",
            ],
            [
                'name' => "Speakenglish2earn BS",
                'description' => "Speakenglish2earn BS",
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
