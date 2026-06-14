<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sutms.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::firstOrCreate(['name' => 'ADMIN']);
        $admin->assignRole($role);

        Role::firstOrCreate(['name' => 'TEACHER']);
        Role::firstOrCreate(['name' => 'STUDENT']);
    }
}
