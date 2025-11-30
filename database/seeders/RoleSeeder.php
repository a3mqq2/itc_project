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
        // Create Admin role
        Role::create([
            'name' => 'admin',
            'ar_name' => 'مدير',
            'guard_name' => 'web'
        ]);

        // Create Receptionist role
        Role::create([
            'name' => 'receptionist',
            'ar_name' => 'موظف استقبال',
            'guard_name' => 'web'
        ]);
    }
}
