<?php

namespace Database\Seeders;

use App\Models\BusinessSector;
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
                'description' => generateRandomText(500),
            ],
            [
                'name' => "learn2earn BS",
                'description' => generateRandomText(500),
            ],
            [
                'name' => "move2earn BS",
                'description' => generateRandomText(500),
            ],
            [
                'name' => "travel2earn BS",
                'description' => generateRandomText(500),
            ],
            [
                'name' => "shop2earn BS",
                'description' => generateRandomText(500),
            ],
            [
                'name' => "beelegant2earn BS",
                'description' => generateRandomText(500),
            ],
            [
                'name' => "Speakenglish2earn BS",
                'description' => generateRandomText(500),
            ],
        ];

        if (BusinessSector::all()->count()) {
            BusinessSector::truncate();
        }

        foreach ($BusinessSectors as $sector) {
            BusinessSector::create($sector);
        }
    }
}
