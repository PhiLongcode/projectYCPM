<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create permissions first
        $this->call(PermissionSeeder::class);

        // 2. Create roles next
        $this->call(RoleSeeder::class);

        // 3. Create users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123')
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        // 4. Create relationships after users exist
        $this->call([
            UserRoleSeeder::class,
            RolePermissionSeeder::class
        ]);
    }
}
