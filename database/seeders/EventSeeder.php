<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Event::create([
                'title' => 'Test Event ' . $i,
                'content' => 'This is the content for test event ' . $i . '.',
                'enabled' => $i % 2 === 0,
                'published_at' => now()->subDays(21 - $i),
                'start_at' => now()->addDays($i),
                'end_at' => now()->addDays($i + 1),
            ]);
        }
    }
}
