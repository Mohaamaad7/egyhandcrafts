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
        User::updateOrCreate(
            ['email' => 'admin@sadat.test'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'role' => 'super_admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }
}
