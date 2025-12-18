<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed a single admin user for local/dev MVP usage.
        // NOTE: Do not use these credentials in production.
        User::query()->updateOrCreate(
            ['email' => 'admin@labourchowk.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $this->call([
            AreaSeeder::class,
            SkillSeeder::class,
            LabourerSeeder::class,
            AvailabilitySeeder::class,
        ]);
    }
}
