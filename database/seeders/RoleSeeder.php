<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    public function run()
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web', 'created_at' => '2021-09-07 12:49:10', 'updated_at' => '2021-09-07 12:49:10'],
            ['name' => 'Moderateur', 'guard_name' => 'web', 'created_at' => '2021-09-07 12:49:10', 'updated_at' => '2021-09-07 12:49:10'],
            ['name' => 'Super admin', 'guard_name' => 'web', 'created_at' => '2021-09-07 12:49:10', 'updated_at' => '2021-09-07 12:49:10'],
            ['name' => 'user', 'guard_name' => 'web', 'created_at' => '2021-09-07 12:49:10', 'updated_at' => '2021-09-07 12:49:10'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
