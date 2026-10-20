<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@primices.test',
            'password' => 'Password123!',
            'role' => 'super_admin',
]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@primices.test',
            'password' => 'Password123!',
            'role' => 'admin',
]);

        User::create([
            'name' => 'Utilisateur',
            'email' => 'user@primices.test',
            'password' => 'Password123!',
            'role' => 'user',
        ]);

        
    }
}
