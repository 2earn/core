<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\DeliveryNotification;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::find(384);
        $user->notify(new DeliveryNotification());
    }
}
