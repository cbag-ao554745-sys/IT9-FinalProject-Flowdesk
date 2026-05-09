<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tasks')->insert([
            // Project 1 - E-Commerce Revamp
            [
                'project_id'       => 1,
                'title'            => 'Database schema design',
                'description'      => 'Design full relational schema for products and orders.',
                'status'           => 'completed',
                'priority'         => 'medium',
                'due_date'         => '2026-03-24',
                'assigned_user_id' => 4,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 1,
                'title'            => 'Implement checkout flow',
                'description'      => 'Build multi-step checkout with cart and payment.',
                'status'           => 'in_progress',
                'priority'         => 'high',
                'due_date'         => '2026-03-28',
                'assigned_user_id' => 6,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 1,
                'title'            => 'Frontend cart component',
                'description'      => 'Reusable cart with real-time quantity updates.',
                'status'           => 'in_progress',
                'priority'         => 'medium',
                'due_date'         => '2026-04-02',
                'assigned_user_id' => 4,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Project 2 - Mobile App v3
            [
                'project_id'       => 2,
                'title'            => 'Push notification setup',
                'description'      => 'Firebase Cloud Messaging for iOS and Android.',
                'status'           => 'on_hold',
                'priority'         => 'low',
                'due_date'         => '2026-04-05',
                'assigned_user_id' => 5,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 2,
                'title'            => 'Offline data sync',
                'description'      => 'Local SQLite caching and background sync.',
                'status'           => 'in_progress',
                'priority'         => 'high',
                'due_date'         => '2026-04-20',
                'assigned_user_id' => 7,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Project 3 - Admin Dashboard
            [
                'project_id'       => 3,
                'title'            => 'User management CRUD',
                'description'      => 'Admin views for managing user accounts.',
                'status'           => 'completed',
                'priority'         => 'high',
                'due_date'         => '2026-03-10',
                'assigned_user_id' => 2,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 3,
                'title'            => 'Reports export to PDF',
                'description'      => 'Downloadable reports using DomPDF.',
                'status'           => 'in_progress',
                'priority'         => 'medium',
                'due_date'         => '2026-03-30',
                'assigned_user_id' => 6,
                'created_by'       => 2,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Project 4 - API Gateway Migration
            [
                'project_id'       => 4,
                'title'            => 'Review API integration PR',
                'description'      => 'Code review of gateway routing layer.',
                'status'           => 'in_progress',
                'priority'         => 'high',
                'due_date'         => '2026-03-22',
                'assigned_user_id' => 2,
                'created_by'       => 3,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 4,
                'title'            => 'Set up rate limiting',
                'description'      => 'Laravel throttle middleware with Redis.',
                'status'           => 'pending',
                'priority'         => 'high',
                'due_date'         => '2026-04-15',
                'assigned_user_id' => 5,
                'created_by'       => 3,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            // Project 5 - User Auth Overhaul
            [
                'project_id'       => 5,
                'title'            => 'Implement MFA with TOTP',
                'description'      => 'Google Authenticator-compatible TOTP.',
                'status'           => 'completed',
                'priority'         => 'high',
                'due_date'         => '2026-03-01',
                'assigned_user_id' => 4,
                'created_by'       => 3,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'project_id'       => 5,
                'title'            => 'OAuth2 SSO integration',
                'description'      => 'Google and Microsoft login via Socialite.',
                'status'           => 'completed',
                'priority'         => 'medium',
                'due_date'         => '2026-03-05',
                'assigned_user_id' => 7,
                'created_by'       => 3,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}