<?php

namespace Database\Seeders;

use App\Models\Hashtag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HashtagSeeder extends Seeder
{

    public function run(): void
    {
        $hashtags = [
            '2earn',
            'order',
            'media',
            'important', // corrected spelling
            'news',
            'surveys',
        ];

        foreach ($hashtags as $name) {
            Hashtag::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => ucfirst($name), 'slug' => Str::slug($name)]
            );
        }
    }

}
