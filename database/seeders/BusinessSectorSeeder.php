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
                'description' => "2earn Business Sectors",
            ],
            [
                'name' => "learn2earn BS",
                'description' => "learn2earn Business Sectors",
            ],
            [
                'name' => "move2earn BS",
                'description' => "move2earn Business Sectors",
            ],
            [
                'name' => "travel2earn BS",
                'description' => "travel2earn Business Sectors",
            ],
            [
                'name' => "shop2earn BS",
                'description' => "shop2earn Business Sectors",
            ],
            [
                'name' => "beelegant2earn BS",
                'description' => "beelegant2earn Business Sectors",
            ],
            [
                'name' => "Speakenglish2earn BS",
                'description' => "Speakenglish2earn Business Sectors",
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
