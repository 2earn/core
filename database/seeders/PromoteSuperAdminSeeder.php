<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class PromoteSuperAdminSeeder extends Seeder
{

    public function run()
    {
        $user = User::find(3716);
        if (!$user) {
            $this->command->error('User not found. Set SUPER_ADMIN_EMAIL or SUPER_ADMIN_ID in your .env or create the user with id 1.');
            return;
        }
        $role = Role::where('name', 'Super admin')->where('guard_name', 'web')->first();
        if (! $role) {
            $this->command->error("Role 'Super admin' not found. Please create it before running this seeder.");
            return;
        }
        $user->syncRoles($role->name);
        $this->command->info("User ({$user->id}, {$user->email}) promoted to role: {$role->name}");
    }
}
