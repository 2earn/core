<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\DeliveryNotification;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(384);
        $user->notify(new DeliveryNotification());
        dd($user->notifications);

    }
}
