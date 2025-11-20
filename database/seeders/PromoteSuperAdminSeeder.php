<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PromoteSuperAdminSeeder extends Seeder
{

    public function promoteUserToSuperAdmin($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->command->error('User not found. Set SUPER_ADMIN_EMAIL or SUPER_ADMIN_ID in your .env or create the user with id 1.');
            return;
        }
        $role = Role::where('name', 'Super admin')->where('guard_name', 'web')->first();
        if (!$role) {
            $this->command->error("Role 'Super admin' not found. Please create it before running this seeder.");
            return;
        }
        $user->syncRoles($role->name);
        $this->command->info("User ({$user->id}, {$user->email}) promoted to role: {$role->name}");
    }

    public function run()
    {
        foreach ([3716, 3786] as $userId) {
            $this->promoteUserToSuperAdmin($userId);
        }
    }
}
