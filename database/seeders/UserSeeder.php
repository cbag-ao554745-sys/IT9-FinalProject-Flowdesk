<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            // Admin
            [
                'name'       => 'System Admin',
                'email'      => 'admin@flowdesk.com',
                'password'   => Hash::make('Admin@12345'),
                'role'       => 'admin',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Project Managers
            [
                'name'       => 'Alex Mendoza',
                'email'      => 'alex.mendoza@flowdesk.com',
                'password'   => Hash::make('Manager@12345'),
                'role'       => 'project_manager',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Maria Santos',
                'email'      => 'maria.santos@flowdesk.com',
                'password'   => Hash::make('Manager@12345'),
                'role'       => 'project_manager',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Team Members
            [
                'name'       => 'Sara Kim',
                'email'      => 'sara.kim@flowdesk.com',
                'password'   => Hash::make('Member@12345'),
                'role'       => 'team_member',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'James Park',
                'email'      => 'james.park@flowdesk.com',
                'password'   => Hash::make('Member@12345'),
                'role'       => 'team_member',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Dana Lim',
                'email'      => 'dana.lim@flowdesk.com',
                'password'   => Hash::make('Member@12345'),
                'role'       => 'team_member',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Carlo Reyes',
                'email'      => 'carlo.reyes@flowdesk.com',
                'password'   => Hash::make('Member@12345'),
                'role'       => 'team_member',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}