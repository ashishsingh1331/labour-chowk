<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Sector 12 Chowk',
            'Old Bus Stand',
            'Main Market',
            'Railway Road',
            'Industrial Area',
            'City Center',
        ];

        foreach ($names as $name) {
            Area::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}
