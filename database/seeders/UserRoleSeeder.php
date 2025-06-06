<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_roles')->insert([
            [
                'user_id' => 1, // Admin user
                'role_id' => 1, // Admin role
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2, // Normal user
                'role_id' => 2, // User role
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
