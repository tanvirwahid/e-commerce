<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = config('admin');

        User::where('email', $admin['admin_email'])->delete();

        $user = User::create([
            'name' => $admin['admin_name'],
            'email' => $admin['admin_email'],
            'password' => $admin['admin_password']
        ]);

        $user->assignRole('admin');
    }
}
