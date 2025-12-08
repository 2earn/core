<?php

namespace Database\Seeders;

use App\Services\Orders\OrderingSimulation;
use Core\Models\Platform;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        $platforms = Platform::all();
        foreach ($platforms as $platform) {
            $numberProduct = rand(1, 10);
            for ($i = 1; $i <= $numberProduct; $i++) {
                OrderingSimulation::createItem($platform->id, $faker);
            }
        }
    }
}
