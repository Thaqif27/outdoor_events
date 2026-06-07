<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@outdoor-events.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'phone' => null,
                'address' => null,
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@outdoor-events.com');
        $this->command->info('Password: Admin@123456');
        $this->command->warn('IMPORTANT: Change this password immediately after first login!');
    }
}