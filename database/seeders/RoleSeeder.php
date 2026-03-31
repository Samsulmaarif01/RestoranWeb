<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin', 'description' => 'Administrator'],
            ['role_name' => 'cashier', 'description' => 'kasir'],
            ['role_name' => 'chef', 'description' => 'koki'],
            ['role_name' => 'customer', 'description' => 'pelanggan'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['role_name' => $role['role_name']], 
                ['description' => $role['description']]
            );
        }
    }
}