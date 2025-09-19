<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $output = new ConsoleOutput();
        $users = User::all();
        $count = 0;
        foreach ($users as $user) {
            if ($user->getRoleNames()->count() == 0) {
                if (app()->environment('local')) {
                    $output->writeln(json_encode($user->id . ' : ' . getUserDisplayedName($user->idUser)));
                }
                $count++;
                $user->assignRole(4);
                $user->save();
            }
        }
        $output->writeln(json_encode('Number  : ' . $count));
    }
}
