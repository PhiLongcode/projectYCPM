<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả permission IDs
        $permissionIds = DB::table('permissions')->pluck('id');

        // Gán tất cả permissions cho role admin (role_id = 1)
        foreach ($permissionIds as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => 1, // Admin role
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}