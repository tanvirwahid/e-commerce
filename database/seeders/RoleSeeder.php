<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $user = Role::where('name', 'user')->first();

        if (! $admin) {
            Role::create([
                'name' => 'admin',
            ]);
        }

        if (! $user) {
            Role::create([
                'name' => 'user',
            ]);
        }
    }
}
