<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Arief',
            'email' => 'arief@arief.com',
            'password' => bcrypt('password'), // Use bcrypt for password hashing
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'level' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Budi',
            'email' => 'manager@arief.com',
            'password' => bcrypt('password'), // Use bcrypt for password hashing
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'level' => 'manager',
        ]);

        User::factory()->create([
            'name' => 'staff',
            'email' => 'staff@arief.com',
            'password' => bcrypt('password'), // Use bcrypt for password hashing
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'level' => 'staff',
        ]);
    }
}
