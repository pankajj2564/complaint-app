<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Default Admin User
        User::create([
            'name' => 'System Admin',
            'email' => 'complaints@cgcuniversity.in',
            'password' => Hash::make('Demo@1234!'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Seed Categories and Sub-categories
        $this->call([
            CategorySeeder::class,
        ]);
    }
}