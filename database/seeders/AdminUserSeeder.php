<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the default local administrator account (idempotent).
     *
     * Uses updateOrCreate() so re-running the seeder does not duplicate
     * or throw a unique-constraint error on the `users.email` column.
     */
    public function run(): void
    {
        // Guard: Prevent seeding default development credentials in production
        if (app()->isProduction()) {
            $this->command?->warn('AdminUserSeeder: Default admin credentials cannot be seeded in production.');
            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@sadat.test'],
            [
                'name' => 'Administrator',
                'job_title' => 'مدير النظام والمشرف العام',
                'username' => 'admin',
                'role' => 'super_admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }
}
