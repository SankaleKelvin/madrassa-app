<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'System administrator with full access',
        ]);

        Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Regular user with limited access',
        ]);

        
        Role::create([
            'name' => 'Student',
            'slug' => 'student',
            'description' => 'Student user with student-related access',
        ]);
    }
}
