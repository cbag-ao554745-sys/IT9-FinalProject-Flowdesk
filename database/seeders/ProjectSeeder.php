<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'name'        => 'E-Commerce Revamp',
                'description' => 'Full redesign of the e-commerce platform.',
                'start_date'  => '2026-01-15',
                'due_date'    => '2026-04-15',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'manager_id'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Mobile App v3',
                'description' => 'Third major version of the mobile application.',
                'start_date'  => '2026-02-01',
                'due_date'    => '2026-05-30',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'manager_id'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Admin Dashboard',
                'description' => 'Internal admin panel for managing users and orders.',
                'start_date'  => '2026-01-10',
                'due_date'    => '2026-04-01',
                'status'      => 'in_progress',
                'priority'    => 'low',
                'manager_id'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'API Gateway Migration',
                'description' => 'Migrate all microservices to a centralized API gateway.',
                'start_date'  => '2026-02-20',
                'due_date'    => '2026-06-10',
                'status'      => 'pending',
                'priority'    => 'high',
                'manager_id'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'User Auth Overhaul',
                'description' => 'Replace session auth with JWT and OAuth2.',
                'start_date'  => '2025-12-01',
                'due_date'    => '2026-03-10',
                'status'      => 'completed',
                'priority'    => 'medium',
                'manager_id'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Data Analytics Pipeline',
                'description' => 'ETL pipeline for sales and user behavior data.',
                'start_date'  => '2026-03-01',
                'due_date'    => '2026-07-31',
                'status'      => 'pending',
                'priority'    => 'medium',
                'manager_id'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}